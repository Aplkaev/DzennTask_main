<?php

namespace App\UseCase\Crud;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly UserRepository $repository
    )
    {
        parent::__construct($em);
    }

    public function createEntityFromArray(array $data): mixed
    {

        $user = new User();
        // todo сохранение пароля
        $user->setEmail($data['email']);
        $user->setAvatarUrl($data['avatar_url']);
        $user->setTimezone($data['timezone']);

        $this->repository->save($user);

        return $user;
    }
    public function updateEntityFromArray(mixed $user, array $data): mixed
    {
        if($user instanceof User === false) { 
            throw new BadRequestException('Is not project item');
        }
        
        $user->setAvatarUrl($data['avatar_url']);
        $user->setTimezone($data['timezone']);
        
        $this->repository->save($user);

        return $user;
    }
}