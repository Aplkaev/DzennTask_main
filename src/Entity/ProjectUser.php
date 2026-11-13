<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Enum\RoleEnum;
use App\Repository\ProjectUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// Участниками проекта управляет только его владелец (PROJECT_MANAGE).
#[ApiResource(
    operations: [],
    graphQlOperations: [
        new QueryCollection(security: "is_granted('ROLE_USER')"),
        new Query(security: "is_granted('PROJECT_VIEW', object.getProject())"),
        new Mutation(
            name: 'create',
            securityPostDenormalize: "is_granted('PROJECT_MANAGE', object.getProject())",
        ),
        new Mutation(
            name: 'update',
            security: "is_granted('PROJECT_MANAGE', object.getProject())",
            securityPostDenormalize: "is_granted('PROJECT_MANAGE', object.getProject())",
        ),
        new DeleteMutation(name: 'delete', security: "is_granted('PROJECT_MANAGE', object.getProject())"),
    ],
)]
#[UniqueEntity(fields: ['project', 'user'], message: 'Пользователь уже участник проекта')]
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
