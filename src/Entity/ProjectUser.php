<?php

namespace App\Entity;

use App\Enum\RoleEnum;
use App\Repository\ProjectUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectUserRepository::class)]
class ProjectUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projectUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?project $project = null;

    #[ORM\ManyToOne(inversedBy: 'projectUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $u = null;

    #[ORM\Column(enumType: RoleEnum::class)]
    private ?RoleEnum $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?project
    {
        return $this->project;
    }

    public function setProject(?project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getU(): ?User
    {
        return $this->u;
    }

    public function setU(?User $u): static
    {
        $this->u = $u;

        return $this;
    }

    public function getRole(): ?RoleEnum
    {
        return $this->role;
    }

    public function setRole(RoleEnum $role): static
    {
        $this->role = $role;

        return $this;
    }
}
