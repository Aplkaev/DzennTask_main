<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CommentDto;
use App\Dto\ProjectDto;
use App\Entity\Comment;
use App\Entity\Project;
use App\UseCase\Crud\CommentCrudUseCase;
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
        protected readonly CommentCrudUseCase $CommentCrudUseCase
    )
    {
        parent::__construct($em, $CommentCrudUseCase);
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