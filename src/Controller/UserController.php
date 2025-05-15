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
use App\Controller\AbstractCrudController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/users')]
class UserController extends AbstractCrudController {     
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly UserCrudUseCase $crudUseCase
    )
    {
        parent::__construct($em, $crudUseCase);
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