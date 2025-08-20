<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BaseEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method find($id, $lockMode = null, $lockVersion = null)
 * @method findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method findOneBy(array $criteria, ?array $orderBy = null)
 */
abstract class BaseEntityRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly ManagerRegistry $registry,
    ) {
        parent::__construct($this->registry, $this->entityClass());
    }

    abstract public function entityClass(): string;

    public function save(BaseEntity $base): BaseEntity
    {
        $this->getEntityManager()->persist($base);
        $this->getEntityManager()->flush();

        return $base;
    }

    public function delete(BaseEntity $base): void
    {
        $this->getEntityManager()->remove($base);
        $this->getEntityManager()->flush();
    }
}
