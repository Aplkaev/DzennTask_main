<?php

declare(strict_types=1);

namespace App\UseCase\Crud;

use App\Entity\User;
use App\Dto\UserRegisterDto;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegisterUseCase
{
    public function __construct(
        protected readonly UserPasswordHasherInterface $passwordHasher,
        protected readonly UserRepository $repository
    ) {}

    public function execute(UserRegisterDto $userDto): User { 

        if($this->repository->findBy(['email'=>$userDto->email])) { 
            throw new BadRequestException('Уже есть такой пользоваетль с email:'.$userDto->email);
        }
        $user = new User();
        $user->setEmail($userDto->email);

        $plaintextPassword = $userDto->password;
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        $user->setPassword($hashedPassword);

        $this->repository->save($user);

        return $user;
    }
}