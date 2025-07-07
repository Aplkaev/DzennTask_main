<?php

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly UserRepository $repository,
    ) {
        parent::__construct($em);
    }

    public function createEntityFromArray(BaseDto|UserDto $dto): mixed
    {
        $user = new User();
        // todo сохранение пароля
        $user->setEmail($dto->email);
        $user->setAvatarUrl($dto->avatarUrl);
        $user->setTimezone($dto->timezone);
        $user->setPassword('password');

        $this->repository->save($user);

        return $user;
    }

    public function updateEntityFromArray(mixed $user, BaseDto|UserDto $dto): mixed
    {
        if (false === $user instanceof User) {
            throw new BadRequestException('Is not project item');
        }

        $user->setAvatarUrl($dto->avatarUrl);
        $user->setTimezone($dto->timezone);

        $this->repository->save($user);

        return $user;
    }
}
