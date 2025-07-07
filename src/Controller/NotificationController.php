<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\NotificationDto;
use App\Entity\Notification;
use App\UseCase\Crud\NotificationCrudUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notification')]
class NotificationController extends AbstractCrudController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly NotificationCrudUseCase $notificationCrudUseCase,
    ) {
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
