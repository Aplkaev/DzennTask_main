<?php

declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\RoleEnum;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

final class TaskCollectionExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private readonly Security $security)
    {
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = [],
    ): void {
        if ($resourceClass !== Task::class) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            $queryBuilder->andWhere('1 = 0');

            return;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        // Владелец проекта (ROLE_OWNER) видит все задачи проекта, обычный участник
        // (ROLE_MEMBER) — только те, что назначены на него.
        $queryBuilder
            ->innerJoin($rootAlias.'.project', 'task_project')
            ->innerJoin('task_project.projectUsers', 'task_project_users')
            ->andWhere('task_project_users.user = :currentTaskUser')
            ->andWhere($queryBuilder->expr()->orX(
                'task_project_users.role = :ownerRole',
                $rootAlias.'.assignedTo = :currentTaskUser',
            ))
            ->setParameter('currentTaskUser', $user)
            ->setParameter('ownerRole', RoleEnum::ROLE_OWNER);
    }
}
