<?php

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Dto\CommentDto;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly CommentRepository $repository,
        protected readonly UserRepository $userRepository,
        protected readonly Security $security,
    ) {
        parent::__construct($em);
    }

    /**
     * @param CommentDto $dto
     */
    public function createEntityFromArray(BaseDto|CommentDto $dto): mixed
    {
        $parent = null;
        $user = $this->security->getUser();
        $entity = null;

        if ($dto->parentId) {
            // TODO вынести в useCase с exception
            $parent = $this->repository->find($dto->parentId);
        }

        if ($dto->entityType && $dto->entityId) {
            $entity = $this->em->getRepository($dto->entityType->value)->find($dto->entityId);
            if (null === $entity) {
                // TODO вынести в свой exception
                throw new NotFoundHttpException("Not found entity: {$dto->entityId} entoty_type:{$dto->entityType->value}");
            }
        }

        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setEntityId($entity->getStringId());
        $comment->setEntityType($dto->entityType);
        $comment->setParent($parent);
        $comment->setText($dto->text);

        $this->repository->save($comment);

        return $comment;
    }

    /**
     * @param CommentDto $dto
     */
    public function updateEntityFromArray(mixed $comment, BaseDto|CommentDto $dto): mixed
    {
        if (false === $comment instanceof Comment) {
            throw new BadRequestException('Is not comment item');
        }

        $comment->setText($dto->text);

        $this->repository->save($comment);

        return $comment;
    }
}
