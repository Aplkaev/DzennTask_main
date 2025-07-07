<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BaseEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseEntityRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly ManagerRegistry $registry,
    ) {
        parent::__construct($registry, $this->entityClass());
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
