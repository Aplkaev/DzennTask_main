<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\UserDto;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class UserAuthUseCase
{
    private ?UserDto $userDto = null;

    private ?User $user = null;

    public function __construct(
        private readonly Security $security,
    ) {
    }

    public function execute(): static
    {
        if ($this->user) {
            return $this;
        }

        $this->user = $this->security->getUser();

        if (null === $this->user) {
            throw new AuthenticationException('Not login');
        }

        $this->userDto = UserDto::fromModel($this->user);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getUserDto(): ?UserDto
    {
        return $this->userDto;
    }
}
