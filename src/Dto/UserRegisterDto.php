<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

final class UserRegisterDto extends BaseDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 500)]
        public string $email {
            get => mb_strtolower($this->email);
            set => $this->email = mb_strtolower($value);
        },
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 500)]
        public readonly ?string $password = null,
        public readonly ?string $id = null,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            email: $data['email'],
            password: $data['password'],
        );
    }

    public static function fromModel(BaseEntity|User $model): static
    {
        return new static(
            email: $model->getEmail(),
            id: $model->getStringId()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
        ];
    }
}
