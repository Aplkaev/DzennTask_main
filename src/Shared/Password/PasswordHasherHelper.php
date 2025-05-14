<?php

declare(strict_types=1);

namespace App\Shared\Password;

use App\Contracts\PasswordHasherInterface;
use App\Entity\Admin;
use App\Entity\UserEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherHelper implements PasswordHasherInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function hash(UserEntity|Admin $userEntity, string $password): string
    {
        return $this->userPasswordHasher->hashPassword($userEntity, $password);
    }
}
