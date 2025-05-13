<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;

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

    public function __construct()
    {
        $this->projectUsers = new ArrayCollection();
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

    public function removeProjectUser(ProjectUser $projectUser): static
    {
        if ($this->projectUsers->removeElement($projectUser)) {
            // set the owning side to null (unless already changed)
            if ($projectUser->getProject() === $this) {
                $projectUser->setProject(null);
            }
        }

        return $this;
    }
}
