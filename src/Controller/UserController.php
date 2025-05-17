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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/users')]
class UserController extends AbstractCrudController {     
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly UserCrudUseCase $crudUseCase,
        protected readonly UserRegisterUseCase $userRegisterUseCase
    )
    {
        parent::__construct($em, $crudUseCase);
    }

    #[Route('/register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), associative: true);
        $item = $this->userRegisterUseCase->execute($data);
       
        return new JsonResponse(data: $this->getDto()::fromModel($item)->jsonSerialize());
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