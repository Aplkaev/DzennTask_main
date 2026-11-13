<?php

declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\KanbanColumn;
use App\Entity\ProjectUser;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Сущности, принадлежащие проекту (поле project): в списках — только из проектов, где текущий пользователь участник.
 */
final class ProjectMemberCollectionExtension implements QueryCollectionExtensionInterface
{
    private const RESOURCES = [KanbanColumn::class, ProjectUser::class];

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
        if (!in_array($resourceClass, self::RESOURCES, true)) {
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

        $queryBuilder
            ->innerJoin($rootAlias.'.project', 'member_project')
            ->innerJoin('member_project.projectUsers', 'member_project_users')
            ->andWhere('member_project_users.user = :currentMemberUser')
            ->setParameter('currentMemberUser', $user->getId(), UuidType::NAME);
    }
}
