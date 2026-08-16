<?php

namespace App\Service;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class NotificationApiService
{
    private Client $client;

    public function __construct(
        private readonly string $notificationBaseUrl,
        private readonly string $apiJwtToken,
        private readonly LoggerInterface $logger,
    ) {
        $this->client = new Client([
            'base_uri' => $this->notificationBaseUrl,
            'timeout' => 5.0,
        ]);
    }

    public function sendNotification(array $payload): bool
    {
        try {
            $response = $this->client->post('/api/notify', [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiJwtToken,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'json' => $payload,
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Throwable $e) {
            $this->logger->error('Notification API error', [
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function readAll(string $userId): array
    {
        try {
            $response = $this->client->post('/api/read/all', [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiJwtToken,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'body' => ['user_id' => $userId],
            ]);

            return $response->getBody();
        } catch (\Throwable $e) {
            $this->logger->error('Notification API error', [
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function read(string $userId, string $messageId): array
    {
        try {
            $response = $this->client->post('/api/read/'.$messageId, [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiJwtToken,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'body' => ['user_id' => $userId],
            ]);

            return $response->getBody();
        } catch (\Throwable $e) {
            $this->logger->error('Notification API error', [
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function getNotificationList(string $userId): array
    {
        try {
            $response = $this->client->get('/api/list', [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiJwtToken,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
            ]);

            return $response->getBody();
        } catch (\Throwable $e) {
            $this->logger->error('Notification API error', [
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
