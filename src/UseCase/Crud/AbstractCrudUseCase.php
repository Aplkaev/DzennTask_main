<?php

declare(strict_types=1);

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCrudUseCase {
    public function __construct(
        protected EntityManagerInterface $em
    ){
        
    }
    
    public function getAll(string $entityClass): mixed
    {
        return $this->em->getRepository($entityClass)->findAll();
    }

    public function getOne(string $entityClass, string $id): mixed
    {
        return $this->em->getRepository($entityClass)->find($id);
    }

    public function create(string $entityClass, BaseDto $dto): mixed
    {
        $item = $this->createEntityFromArray($dto);
        $this->em->persist($item);
        $this->em->flush();

        return $item;
    }

    public function update(string $entityClass, string $id, BaseDto $dto): mixed
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