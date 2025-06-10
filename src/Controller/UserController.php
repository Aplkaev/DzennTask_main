<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Entity\User;
use App\Dto\ProjectDto;
use App\Entity\Project;
use App\UseCase\Crud\UserCrudUseCase;
use App\UseCase\Crud\ProjectCrudUseCase;
use Doctrine\ORM\EntityManagerInterface;
use App\UseCase\Crud\AbstractCrudUseCase;
use App\UseCase\Crud\UserRegisterUseCase;
use App\Controller\AbstractCrudController;
use App\Dto\UserRegisterDto;
use App\UseCase\User\UserAuthUseCase;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/users')]
class UserController extends AbstractCrudController {     
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly UserCrudUseCase $crudUseCase,
        protected readonly UserRegisterUseCase $userRegisterUseCase,
        protected readonly UserAuthUseCase $userAuthUseCase
    )
    {
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