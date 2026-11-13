<?php

declare(strict_types=1);

namespace App\State\Comment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * createComment: автор комментария — всегда текущий пользователь, с клиента не принимается.
 *
 * @implements ProcessorInterface<Comment, Comment>
 */
final readonly class CommentCreateProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<Comment, Comment> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Comment
    {
        /** @var Comment $data */
        /** @var User $user */
        $user = $this->security->getUser();
        $data->setAuthor($user);

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
