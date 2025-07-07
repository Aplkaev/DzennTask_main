<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\BaseDto;
use App\Shared\Parser\ParseDataTrait;
use App\Shared\Response\ApiResponse;
use App\UseCase\Crud\AbstractCrudUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

abstract class AbstractCrudController extends AbstractController
{
    use ParseDataTrait;

    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly AbstractCrudUseCase $abstractCrudUseCase,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $items = $this->abstractCrudUseCase->getAll($this->entityClass());
        } catch (NotFoundHttpException $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseList(
            self::parseResponseDtoList($this->getDto(), $items),
        );
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        try {
            $item = $this->abstractCrudUseCase->getOne($this->entityClass(), $id);
        } catch (NotFoundHttpException $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(data: $this->getDto()::fromModel($item)->jsonSerialize());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode($request->getContent(), true);
        /**
         * @var BaseDto $dto
         */
        $dto = $this->getDto()::fromArray($data);

        $item = $this->abstractCrudUseCase->create($this->entityClass(), $dto);

        return new JsonResponse($this->getDto()::fromModel($item)->jsonSerialize(), 201);
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /**
         * @var BaseDto $dto
         */
        $dto = $this->getDto()::fromArray($request->getContent());

        $item = $this->abstractCrudUseCase->update($this->entityClass(), $id, $dto);

        return new JsonResponse($this->getDto()::fromModel($item)->jsonSerialize());
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->abstractCrudUseCase->delete($this->entityClass(), $id);

        return new JsonResponse(null, 204);
    }

    abstract public function entityClass(): string;

    abstract public function getDto(): string;
}
