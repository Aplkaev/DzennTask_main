<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\RoleEnum;
use App\Repository\ProjectUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[ApiResource(
    security: "is_granted('IS_AUTHENTICATED_FULLY')"
)]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[ORM\Entity(repositoryClass: ProjectUserRepository::class)]
class ProjectUser extends BaseEntity
{
    #[ORM\ManyToOne(inversedBy: 'projectUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\ManyToOne(inversedBy: 'projectUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(enumType: RoleEnum::class)]
    private RoleEnum $role;

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRole(): RoleEnum
    {
        return $this->role;
    }

    public function setRole(RoleEnum $role): static
    {
        $this->role = $role;

        return $this;
    }
}
