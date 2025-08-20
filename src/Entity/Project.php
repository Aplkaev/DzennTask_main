<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project extends BaseEntity
{
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tag = null;

    /**
     * @var Collection<int, ProjectUser>
     */
    #[ORM\OneToMany(targetEntity: ProjectUser::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $projectUsers;

    /**
     * @var Collection<int, KanbanColumn>
     */
    #[ORM\OneToMany(targetEntity: KanbanColumn::class, mappedBy: 'project')]
    private Collection $kanbanColumns;

    public function __construct()
    {
        $this->projectUsers = new ArrayCollection();
        $this->kanbanColumns = new ArrayCollection();
    }

    // #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectUser::class)]
    // private Collection $projectUsers;

    // #[ORM\OneToMany(mappedBy: 'project', targetEntity: Task::class)]
    // private Collection $tasks;

    // public function addProjectUsers(ProjectUser $projectUser): static
    // {
    //     $this->projectUsers->add($projectUser);

    //     return $this;
    // }

    // public function getProjetUsers(): Collection
    // {
    //     return $this->projectUsers;
    // }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return Collection<int, ProjectUser>
     */
    public function getProjectUsers(): Collection
    {
        return $this->projectUsers;
    }

    public function addProjectUser(ProjectUser $projectUser): static
    {
        if (!$this->projectUsers->contains($projectUser)) {
            $this->projectUsers->add($projectUser);
            $projectUser->setProject($this);
        }

        return $this;
    }

    // public function removeProjectUser(ProjectUser $projectUser): static
    // {
    //     if ($this->projectUsers->removeElement($projectUser)) {
    //         // set the owning side to null (unless already changed)
    //         if ($projectUser->getProject() === $this) {
    //             $projectUser->setProject(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, KanbanColumn>
     */
    public function getKanbanColumns(): Collection
    {
        return $this->kanbanColumns;
    }

    public function addKanbanColumn(KanbanColumn $kanbanColumn): static
    {
        if (!$this->kanbanColumns->contains($kanbanColumn)) {
            $this->kanbanColumns->add($kanbanColumn);
            $kanbanColumn->setProject($this);
        }

        return $this;
    }

    // public function removeKanbanColumn(KanbanColumn $kanbanColumn): static
    // {
    //     if ($this->kanbanColumns->removeElement($kanbanColumn)) {
    //         // set the owning side to null (unless already changed)
    //         if ($kanbanColumn->getProject() === $this) {
    //             $kanbanColumn->setProject(null);
    //         }
    //     }

    //     return $this;
    // }
}
