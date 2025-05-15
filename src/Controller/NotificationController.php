<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ProjectDto;
use App\Entity\Project;
use App\Dto\NotificationDto;
use App\Entity\Notification;
use App\UseCase\Crud\ProjectCrudUseCase;
use Doctrine\ORM\EntityManagerInterface;
use App\UseCase\Crud\AbstractCrudUseCase;
use App\Controller\AbstractCrudController;
use App\UseCase\Crud\NotificationCrudUseCase;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/notification')]
class NotificationController extends AbstractCrudController {     
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly NotificationCrudUseCase $notificationCrudUseCase
    )
    {
        parent::__construct($em, $notificationCrudUseCase);
    }

    public function entityClass(): string
    {
        return Notification::class;
    }

    public function getDto(): string
    {
        return NotificationDto::class;
    }
}