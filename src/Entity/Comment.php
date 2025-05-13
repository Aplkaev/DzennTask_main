<?php

namespace App\Entity;

use App\Enum\AllEntityTypeEnum;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment extends BaseEntity
{
    #[ORM\ManyToOne(inversedBy: 'content')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(enumType: AllEntityTypeEnum::class)]
    private ?AllEntityTypeEnum $entityType = null;

    #[ORM\ManyToOne]
    private ?Comment $parent = null;

    #[ORM\Column]
    private ?int $entityId = null;

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
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

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): static
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
}
