<?php

declare(strict_types=1);

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Entity\BaseEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractCrudUseCase {
    public function __construct(
        protected EntityManagerInterface $em
    ){
        
    }
    
    /**
     * @return BaseEntity[]
     */
    public function getAll(string $entityClass): array
    {
        $entitys = $this->em->getRepository($entityClass)->findAll();

        if($entitys === null) { 
            throw new NotFoundHttpException('Not found');
        }

        return $entitys;
    }

    public function getOne(string $entityClass, string $id): BaseEntity
    {
        $entity = $this->em->getRepository($entityClass)->find($id);

        if($entity === null) { 
            throw new NotFoundHttpException('Not found');
        }
        
        return $entity;
    }

    public function create(string $entityClass, BaseDto $dto): BaseEntity
    {
        $item = $this->createEntityFromArray($dto);
        $this->em->persist($item);
        $this->em->flush();

        return $item;
    }

    public function update(string $entityClass, string $id, BaseDto $dto): BaseEntity
    {
        $item = $this->em->getRepository($entityClass)->find($id);
        $this->updateEntityFromArray($item, $dto);
        $this->em->flush();

        return $item;
    }

    public function delete(string $entityClass, string $id): void
    {
        $item = $this->em->getRepository($entityClass)->find($id);
        $this->em->remove($item);
        $this->em->flush();
    }

    public abstract function createEntityFromArray(BaseDto $dto): mixed;
    public abstract function updateEntityFromArray(mixed $item, BaseDto $dto): mixed;
}