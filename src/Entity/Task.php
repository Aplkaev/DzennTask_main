<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task extends BaseEntity
{

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descrition = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?int $priority = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deadline = null;

    #[ORM\Column(nullable: true)]
    private ?int $storyPounts = null;

    #[ORM\ManyToOne]
    private ?Task $parent = null;

    #[ORM\ManyToOne]
    private Project $project;

    #[ORM\ManyToOne]
    private ?User $assignedTo = null;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
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

    public function setAssgnedTo(?User $assignedTo): static
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    public function getAssgnedTo(): ?User
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

    public function getDescrition(): ?string
    {
        return $this->descrition;
    }

    public function setDescrition(?string $descrition): static
    {
        $this->descrition = $descrition;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
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

    public function getDeadline(): ?\DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTime $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getStoryPounts(): ?int
    {
        return $this->storyPounts;
    }

    public function setStoryPounts(?int $storyPounts): static
    {
        $this->storyPounts = $storyPounts;

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
}
