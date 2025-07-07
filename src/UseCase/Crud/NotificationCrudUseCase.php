<?php

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use Doctrine\ORM\EntityManagerInterface;

class NotificationCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
    ) {
        parent::__construct($em);
    }

    public function createEntityFromArray(BaseDto $dto): mixed
    {
        return null;
    }

    public function updateEntityFromArray(mixed $comment, BaseDto $dto): mixed
    {
        return null;
    }
}
