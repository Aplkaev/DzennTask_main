<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Dto\UserRegisterDto;
use App\Entity\User;
use App\UseCase\Crud\UserCrudUseCase;
use App\UseCase\Crud\UserRegisterUseCase;
use App\UseCase\User\UserAuthUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UserController extends AbstractCrudController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly UserCrudUseCase $crudUseCase,
        protected readonly UserRegisterUseCase $userRegisterUseCase,
        protected readonly UserAuthUseCase $userAuthUseCase,
    ) {
        parent::__construct($em, $crudUseCase);
    }

    #[Route('/register', methods: ['POST'])]
    public function register(Request $request, #[MapRequestPayload] UserRegisterDto $user): JsonResponse
    {
        $item = $this->userRegisterUseCase->execute($user);

        return new JsonResponse(data: $this->getDto()::fromModel($item)->jsonSerialize());
    }

    #[Route('/me', methods: ['GET'])]
    public function me(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->userAuthUseCase->execute()->getUser();

        return new JsonResponse(data: $this->getDto()::fromModel($user)->jsonSerialize());
    }

    public function entityClass(): string
    {
        return User::class;
    }

    public function getDto(): string
    {
        return UserDto::class;
    }
}
