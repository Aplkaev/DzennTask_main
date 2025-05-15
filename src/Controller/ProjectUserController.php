<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\UseCase\Crud\ProjectUserCrudUseCase;
use Doctrine\ORM\EntityManagerInterface;
use App\UseCase\Crud\AbstractCrudUseCase;
use App\Controller\AbstractCrudController;
use App\Dto\ProjectDto;
use App\Dto\ProjectUserDto;
use App\Entity\ProjectUser;
use App\Entity\Task;
use App\UseCase\Crud\ProjectCrudUseCase;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/task')]
class ProjectUserController extends AbstractCrudController {     
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly ProjectUserCrudUseCase $projectCrudUseCase
    )
    {
        parent::__construct($em, $projectCrudUseCase);
    }

    public function entityClass(): string
    {
        return ProjectUser::class;
    }

    public function getDto(): string
    {
        return ProjectUserDto::class;
    }
}