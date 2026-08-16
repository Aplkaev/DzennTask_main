<?php

namespace App\Controller;

use App\Message\NotificationMessage;
use App\Shared\Response\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class TestContoller extends AbstractController
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {

    }

    #[Route('test', name: 'test')]
    public function testAction(): JsonResponse
    {
        $this->bus->dispatch(new NotificationMessage(
            type: 'test',
            name: 'name_test',
            payload:['body' => 'text'],
            userId: 'user_id'
        ));

        return ApiResponse::success();
    }
}
