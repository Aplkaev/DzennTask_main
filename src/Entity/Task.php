<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Enum\Task\TaskStatusEnum;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(securityPostDenormalize: "is_granted('TASK_CREATE', object)"),

        new Get(security: "is_granted('TASK_VIEW', object)"),
        new Put(security: "is_granted('TASK_EDIT', object)"),
        new Delete(security: "is_granted('TASK_DELETE', object)"),
    ],
    graphQlOperations: [
        new QueryCollection(),
        new Query(security: "is_granted('TASK_VIEW', object)"),

        new Mutation(name: 'create', securityPostDenormalize: "is_granted('TASK_CREATE', object)"),
        new Mutation(name: 'update', security: "is_granted('TASK_EDIT', object)"),
        new DeleteMutation(name: 'delete', security: "is_granted('TASK_DELETE', object)"),
    ],
)]
#[ApiFilter(SearchFilter::class, properties: [
    'project' => 'exact',
    'status' => 'exact',
    'title' => 'partial',
    'description' => 'partial',
])]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task extends BaseEntity implements PublishableEventEntity
{
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 10000, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(enumType: TaskStatusEnum::class)]
    private ?TaskStatusEnum $status = null;

    #[ORM\Column]
    private ?int $priority = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deadline = null;

    #[ORM\Column(nullable: true)]
    private ?int $storyPoints = null;

    #[ORM\ManyToOne]
    private ?Task $parent = null;

    #[ORM\ManyToOne]
    private Project $project;

    #[ORM\ManyToOne]
    private ?User $assignedTo = null;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: true)]
    private ?KanbanColumn $kanbanColumn = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function addComments(Comment $comment): static
    {
        $this->comments->add($comment);

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setAssignedTo(?User $assignedTo): static
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setProject(Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setParent(?Task $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ?Task
    {
        return $this->parent;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?TaskStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?TaskStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getDeadline(): ?\DateTimeImmutable
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTimeImmutable $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getStoryPoints(): ?int
    {
        return $this->storyPoints;
    }

    public function setStoryPoints(?int $storyPoints): static
    {
        $this->storyPoints = $storyPoints;

        return $this;
    }

    public function getKanbanColumn(): ?KanbanColumn
    {
        return $this->kanbanColumn;
    }

    public function setKanbanColumn(?KanbanColumn $kanbanColumn): static
    {
        $this->kanbanColumn = $kanbanColumn;

        return $this;
    }

    public function toEventPayload(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'project_id' => $this->getProject()->getId(),
            'assigned_to_id' => $this->getAssignedTo()?->getId(),
            'status' => $this->getStatus()?->value,
            'priority' => $this->getPriority(),
            'story_points' => $this->getStoryPoints(),
            'deadline' => $this->getDeadline()?->format(\DateTimeInterface::ATOM),
            'description' => $this->getDescription(),
        ];
    }
}
