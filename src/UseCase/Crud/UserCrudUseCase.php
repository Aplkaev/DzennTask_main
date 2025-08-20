<?php

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly UserRepository $repository,
    ) {
        parent::__construct($em);
    }

    public function createEntityFromArray(BaseDto $dto): User
    {
        if (!$dto instanceof UserDto) {
            throw new \InvalidArgumentException('Expected UserDto, got '.get_class($dto));
        }

        $user = new User();
        $user->setEmail($dto->email);
        $user->setAvatarUrl($dto->avatarUrl);
        $user->setTimezone($dto->timezone);
        $user->setPassword('password');

        $this->repository->save($user);

        return $user;
    }

    /**
     * Undocumented function.
     */
    public function updateEntityFromArray(mixed $user, BaseDto|UserDto $dto): User
    {
        if (!$dto instanceof UserDto) {
            throw new \InvalidArgumentException('Expected UserDto, got '.get_class($dto));
        }

        if (!$user instanceof User) {
            throw new \InvalidArgumentException('Expected User, got '.get_class($user));
        }

        $user->setAvatarUrl($dto->avatarUrl);
        $user->setTimezone($dto->timezone);

        $this->repository->save($user);

        return $user;
    }
}
