<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Enum\AllEntityTypeEnum;
use App\Repository\CommentRepository;
use App\State\Comment\CommentCreateProcessor;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [],
    graphQlOperations: [
        new QueryCollection(security: "is_granted('ROLE_USER')"),
        new Query(security: "is_granted('ROLE_USER') and object.getAuthor().getId().equals(user.getId())"),
        new Mutation(
            name: 'create',
            security: "is_granted('ROLE_USER')",
            processor: CommentCreateProcessor::class,
        ),
        new Mutation(name: 'update', security: "is_granted('ROLE_USER') and object.getAuthor().getId().equals(user.getId())"),
        new DeleteMutation(name: 'delete', security: "is_granted('ROLE_USER') and object.getAuthor().getId().equals(user.getId())"),
    ],
)]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment extends BaseEntity implements PublishableEventEntity
{
    #[ORM\ManyToOne(inversedBy: 'content')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(writable: false)]
    private User $author;

    #[ORM\Column(enumType: AllEntityTypeEnum::class)]
    private AllEntityTypeEnum $entityType;

    #[ORM\ManyToOne]
    private ?Comment $parent = null;

    #[ORM\Column]
    private string $entityId;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getEntityType(): ?AllEntityTypeEnum
    {
        return $this->entityType;
    }

    public function setEntityType(AllEntityTypeEnum $entityType): static
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function getEntityId(): ?string
    {
        return $this->entityId;
    }

    public function setEntityId(string $entityId): static
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function setParent(?Comment $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ?Comment
    {
        return $this->parent;
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

    public function toEventPayload(): array
    {
        return [
            'id' => $this->getId(),
            'author_id' => $this->getAuthor()->getId(),
            'entity_type' => $this->getEntityType()->value,
            'entity_id' => $this->getEntityId(),
            'text' => $this->getText(),
        ];
    }
}
