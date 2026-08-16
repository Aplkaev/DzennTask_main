<?php

declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

final class ProjectCollectionExtension implements QueryCollectionExtensionInterface
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
        if ($resourceClass !== Project::class) {
            return;
        }
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            $queryBuilder->andWhere('1 = 0');

            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        // Проверяем, не присоединена ли уже таблица projectUsers
        $joinAlreadyExists = false;
        foreach ($queryBuilder->getDQLPart('join') as $joins) {
            foreach ($joins as $join) {
                if ($join->getJoin() === $rootAlias.'.projectUsers') {
                    $joinAlreadyExists = true;
                    break 2;
                }
            }
        }

        if (!$joinAlreadyExists) {
            $queryBuilder->innerJoin($rootAlias.'.projectUsers', 'pus');
        }

        $queryBuilder
            ->andWhere('pus.user = :currentUser')
            ->setParameter('currentUser', $user);
    }
}
