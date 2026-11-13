<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

// Уведомления создаёт система; пользователь может только читать свои, отмечать прочитанными и удалять.
#[ApiResource(
    operations: [],
    graphQlOperations: [
        new QueryCollection(security: "is_granted('ROLE_USER')"),
        new Query(security: "is_granted('ROLE_USER') and object.getUser().getId().equals(user.getId())"),
        new Mutation(
            name: 'update',
            security: "is_granted('ROLE_USER') and object.getUser().getId().equals(user.getId())",
            denormalizationContext: ['groups' => ['notification:update']],
        ),
        new DeleteMutation(name: 'delete', security: "is_granted('ROLE_USER') and object.getUser().getId().equals(user.getId())"),
    ],
)]
#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification extends BaseEntity
{
    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    #[ORM\Column]
    #[Groups(['notification:update'])]
    private ?bool $isRead = null;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): static
    {
        $this->isRead = $isRead;

        return $this;
    }
}
