<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\BaseDto;
use App\Entity\Project;
use App\Shared\Response\ApiResponse;
use App\Shared\Parser\ParseDataTrait;
use Doctrine\ORM\EntityManagerInterface;
use App\UseCase\Crud\AbstractCrudUseCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractCrudController extends AbstractController{ 
    use ParseDataTrait;

    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly AbstractCrudUseCase $abstractCrudUseCase
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $items = $this->abstractCrudUseCase->getAll($this->entityClass());
        return ApiResponse::responseList(
            self::parseResponseDtoList($this->getDto(), $items),
        );
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $item = $this->abstractCrudUseCase->getOne($this->entityClass(), $id);
        return new JsonResponse(data: $this->getDto()::fromModel($item)->jsonSerialize());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $item = $this->abstractCrudUseCase->create($this->entityClass(), $request);
        return new JsonResponse($this->getDto()::fromModel($item)->jsonSerialize(), 201);
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $item = $this->abstractCrudUseCase->update($this->entityClass(), $id, $request);
        return new JsonResponse($this->getDto()::fromModel($item)->jsonSerialize());
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->abstractCrudUseCase->delete($this->entityClass(), $id);
        return new JsonResponse(null, 204);
    }

    public abstract function entityClass(): string;

    public abstract function getDto(): string;
}