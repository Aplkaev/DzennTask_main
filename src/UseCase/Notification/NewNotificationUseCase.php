<?php

declare(strict_types=1);

namespace App\UseCase\Notification;

use App\Dto\NotificationDto;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class NewNotificationUseCase
{
    public function __construct(
        private readonly NotificationRepository $notificationRepository,
        private readonly UserRepository $userRepository,
        private readonly MessageBusInterface $messageBus,
    ) {

    }

    public function execute(NotificationDto $dto): void
    {

        $user = $this->userRepository->find($dto->userId);
        $notification = new Notification();
        $notification->setText($dto->text);
        $notification->setType($dto->type);
        $notification->setUser($user);

        $this->notificationRepository->save($notification);

        $this->messageBus->dispatch();
    }
}
