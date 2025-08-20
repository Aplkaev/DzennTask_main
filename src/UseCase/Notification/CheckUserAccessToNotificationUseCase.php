<?php

declare(strict_types=1);

namespace App\UseCase\Notification;

use App\Entity\User;
use App\Repository\NotificationRepository;
use App\UseCase\User\UserAuthUseCase;

class CheckUserAccessToNotificationUseCase
{
    public function __construct(
        private readonly UserAuthUseCase $userAuthUseCase,
        private readonly NotificationRepository $notificationRepository,
    ) {
    }

    public function execute(string $notificationId, ?User $user = null): bool
    {
        if ($user === null) {
            $user = $this->userAuthUseCase->execute()->getUser();
        }

        $notification = $this->notificationRepository->createQueryBuilder('n')
            ->andWhere('n.id = :notificationId')
            ->andWhere('n.user = :user')
            ->setParameter('notificationId', $notificationId)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();

        return $notification !== null;
    }
}
