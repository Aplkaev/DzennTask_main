<?php

namespace App\UseCase\Crud;

use App\Entity\Comment;
use App\Entity\Project;
use App\Enum\AllEntityTypeEnum;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Maker\Common\EntityIdTypeEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CommenCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly CommentRepository $repository,
        protected readonly UserRepository $userRepository,
    )
    {
        parent::__construct($em);
    }

    public function createEntityFromArray(array $data): mixed
    {
        $parent = null;
        $user = null;
        $entity = null;

        if(isset($data['parent_id']) && $data['parent_id']) { 
            // TODO вынести в useCase с exception
            $parent = $this->repository->find($data['parent_id']);
        }

        if(isset($data['user_id']) && $data['user_id']) { 
            // TODO вынести в useCase с exception
            $user = $this->userRepository->find($data['user_id']);
            if($user === null) { 
                // TODO вынести в свой exception
                throw new NotFoundHttpException('Not found user:'.$data['user_id']);
            }
        }

        if(
            isset($data['entity_id']) && 
            isset($data['entity_type']) && 
            $data['entity_type'] && 
            $data['entity_id']
        ) { 
            $entity = $this->em->getRepository(AllEntityTypeEnum::from($data['entity_type'])->value)->find($data['entity_id']);
            if($entity === null) { 
                // TODO вынести в свой exception
                throw new NotFoundHttpException("Not found entity: {$data['entity_id']} entoty_type:{$data['entity_type']}");
            }
        }
        

        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setEntityId($entity->getStringId());
        $comment->setEntityType(AllEntityTypeEnum::from($data['entity_type']));
        $comment->setParent($parent);
        $comment->setText($data['text']);

        $this->repository->save($comment);

        return $comment;
    }
    public function updateEntityFromArray(mixed $comment, array $data): mixed
    {
        if($comment instanceof Comment === false) { 
            throw new BadRequestException('Is not comment item');
        }
        
        $comment->setText($data['text']);
        
        $this->repository->save($comment);

        return $comment;
    }
}