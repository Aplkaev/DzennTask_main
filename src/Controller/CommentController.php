<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CommentDto;
use App\Entity\Comment;
use App\UseCase\Crud\CommentCrudUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractCrudController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly CommentCrudUseCase $CommentCrudUseCase,
    ) {
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
