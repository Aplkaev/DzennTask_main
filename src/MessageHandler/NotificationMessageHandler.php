<?php

namespace App\MessageHandler;
use App\Message\NotificationMessage;
use App\UseCase\NotificationMessage\SaveNotificationMessageUseCase;
use App\UseCase\NotificationMessage\SendNotificationMessageUseCase;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class NotificationMessageHandler
{
    public function __construct(
//        private readonly SaveNotificationMessageUseCase $saveNotificationMessageUseCase,
//        private readonly SendNotificationMessageUseCase $sendNotificationMessageUseCase
    )
    {

    }

    public function __invoke(NotificationMessage $message): void
    {
        // сохраняем сообщение к себе
//        $messageList = $this->saveNotificationMessageUseCase->execute($message);
//
//        // делаем отправку по каналу
//        $this->sendNotificationMessageUseCase->execute($messageList);
    }
}
