<?php

declare(strict_types=1);

namespace App\UseCase\Notification;

use App\Entity\User;
use App\Exception\AccessDenied\AccessDeniedException;

class VerifyUserAccessToNotificationUseCase
{
    public function __construct(
        private readonly CheckUserAccessToNotificationUseCase $checkUserAccessToNotificationUseCase,
    ) {
    }

    public function execute(string $notificationId, bool $exception = true, ?User $user = null): bool
    {
        $result = $this->checkUserAccessToNotificationUseCase->execute($notificationId, $user);
        if ($exception && $result === false) {
            throw new AccessDeniedException('User has no access to this notification');
        }

        return $result;
    }
}
