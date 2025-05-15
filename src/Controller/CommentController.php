<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\Crud\CommenCrudUseCase;
use Dom\Comment;
use App\Dto\CommentDto;
use App\Dto\ProjectDto;
use App\Entity\Project;
use App\UseCase\Crud\ProjectCrudUseCase;
use Doctrine\ORM\EntityManagerInterface;
use App\UseCase\Crud\AbstractCrudUseCase;
use App\Controller\AbstractCrudController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/comment')]
class CommentController extends AbstractCrudController {     
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly CommenCrudUseCase $commenCrudUseCase
    )
    {
        parent::__construct($em, $commenCrudUseCase);
    }

    public function entityClass(): string
    {
        return Comment::class;
    }

    public function getDto(): string
    {
        return CommentDto::class;
    }
}