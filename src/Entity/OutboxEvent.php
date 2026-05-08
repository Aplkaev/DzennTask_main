<?php

namespace App\Entity;

use App\Repository\OutboxEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OutboxEventRepository::class)]
#[ORM\Table(name: 'outbox_events')]
#[ORM\Index(columns: ['processed_at'])]
class OutboxEvent extends BaseEntity
{
    #[ORM\Column(type: 'string', length: 100)]
    private string $type;

    #[ORM\Column(type: 'json')]
    private array $payload = [];

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $processedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function markProcessed(): void
    {
        $this->processedAt = new \DateTimeImmutable();
    }

    public function isProcessed(): bool
    {
        return $this->processedAt !== null;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
