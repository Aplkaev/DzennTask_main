<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ProjectDto;
use App\Entity\Project;
use App\UseCase\Crud\ProjectCrudUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project')]
class ProjectController extends AbstractCrudController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly ProjectCrudUseCase $projectCrudUseCase,
    ) {
        parent::__construct($em, $projectCrudUseCase);
    }

    public function entityClass(): string
    {
        return Project::class;
    }

    public function getDto(): string
    {
        return ProjectDto::class;
    }
}
