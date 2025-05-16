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
}
