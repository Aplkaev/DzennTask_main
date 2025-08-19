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

    /**
     * Undocumented function.
     *
     * @param UserDto $dto
     */
    public function createEntityFromArray(BaseDto|UserDto $dto): User
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

    /**
     * @pamar User $user
     *
     * @param UserDto $dto
     *
     * @return User
     */
    public function updateEntityFromArray(mixed $user, BaseDto|UserDto $dto): User
    {

        $user->setAvatarUrl($dto->avatarUrl);
        $user->setTimezone($dto->timezone);

        $this->repository->save($user);

        return $user;
    }
}
