<?php

namespace App\Message;

class NotificationMessage
{
    public function __construct(
        public string $type,
        public string $name,
        public array $payload,
        public string $userId
    )
    {
    }
}
