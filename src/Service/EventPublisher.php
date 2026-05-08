<?php

namespace App\Service;

use App\Entity\OutboxEvent;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;

class EventPublisher
{
    private AMQPChannel $channel;

    public function __construct(
        AMQPStreamConnection $connection
    ) {
        $this->channel = $connection->channel();

        $this->channel->exchange_declare(
            exchange: 'events',
            type: 'topic',
            passive: false,
            durable: true,
            auto_delete: false
        );
    }

    public function publish(OutboxEvent $event): void
    {
        $message = new AMQPMessage(
            body: json_encode([
                'event_id' => $event->getId(),
                'event_name' => $event->getType(),
                'payload' => $event->getPayload(),
            ], JSON_THROW_ON_ERROR),
            properties: [
                'content_type' => 'application/json',
                'delivery_mode' => 2,
            ]
        );

        $this->channel->basic_publish(
            msg: $message,
            exchange: 'events',
            routing_key: $event->getType()
        );
    }
}