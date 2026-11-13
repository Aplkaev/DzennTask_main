<?php

declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Comment;
use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Личные сущности: в списках — только принадлежащие текущему пользователю.
 */
final class OwnCollectionExtension implements QueryCollectionExtensionInterface
{
    /** Класс ресурса => поле с владельцем */
    private const OWNER_FIELDS = [
        Comment::class => 'author',
        Notification::class => 'user',
    ];

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
        $field = self::OWNER_FIELDS[$resourceClass] ?? null;
        if ($field === null) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            $queryBuilder->andWhere('1 = 0');

            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->andWhere(sprintf('%s.%s = :currentOwner', $rootAlias, $field))
            ->setParameter('currentOwner', $user->getId(), UuidType::NAME);
    }
}
