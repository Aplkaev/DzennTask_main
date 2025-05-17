<?php

declare(strict_types=1);

namespace App\UseCase\Crud;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegisterUseCase
{
    public function __construct(
        protected readonly UserPasswordHasherInterface $passwordHasher,
        protected readonly UserRepository $repository
    ) {}

    public function execute(array $data): User { 

        $user = new User();
        $user->setEmail($data['email']);
        $user->setAvatarUrl($data['avatar_url']);
        $user->setTimezone($data['timezone']);

        $plaintextPassword = $data['password'];

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        $user->setPassword($hashedPassword);

        $this->repository->save($user);

        return $user;
    }
}