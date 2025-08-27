<?php

namespace App\Repository;

use App\Dto\Filter\Task\FilterTaskDto;
use App\Entity\Task;

class TaskRepository extends BaseEntityRepository
{
    public function entityClass(): string
    {
        return Task::class;
    }

    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @return Task[]
     */
    public function findByFilters(FilterTaskDto $filter): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($filter->getProjectId()) {
            $qb->andWhere('t.project = :project')
               ->setParameter('project', $filter->getProjectId());
        }

        if ($filter->getStatus()) {
            $qb->andWhere('t.status = :status')
               ->setParameter('status', $filter->getStatus()->value);
        }

        if ($filter->getText()) {
            $qb->andWhere('LOWER(t.title) LIKE :text OR LOWER(t.descrition) LIKE :text')
               ->setParameter('text', '%'.mb_strtolower($filter->getText()).'%');
        }

        $qb->orderBy('t.priority', 'DESC')
           ->addOrderBy('t.id', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
