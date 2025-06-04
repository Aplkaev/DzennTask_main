<?php

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Entity\Comment;
use App\Entity\Project;
use App\Enum\AllEntityTypeEnum;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\NotificationRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Maker\Common\EntityIdTypeEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class NotificationCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
    )
    {
        parent::__construct($em);
    }

    public function createEntityFromArray(BaseDto $dto): mixed
    {
        return null;
    }
    public function updateEntityFromArray(mixed $comment, BaseDto $dto): mixed
    {
        return null;
    }
}