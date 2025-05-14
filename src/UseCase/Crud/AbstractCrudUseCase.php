<?php

declare(strict_types=1);

namespace App\UseCase\Crud;

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

    public function create(string $entityClass, Request $request): mixed
    {
        $data = json_decode($request->getContent(), true);
        $item = $this->createEntityFromArray($data);
        $this->em->persist($item);
        $this->em->flush();

        return $item;
    }

    public function update(string $entityClass, string $id, Request $request): mixed
    {
        $item = $this->em->getRepository($entityClass)->find($id);
        $data = json_decode($request->getContent(), true);
        $this->updateEntityFromArray($item, $data);
        $this->em->flush();

        return $item;
    }

    public function delete(string $entityClass, string $id): void
    {
        $item = $this->em->getRepository($entityClass)->find($id);
        $this->em->remove($item);
        $this->em->flush();
    }

    public abstract function createEntityFromArray(array $data): mixed;
    public abstract function updateEntityFromArray(mixed $item, array $data): mixed;
}