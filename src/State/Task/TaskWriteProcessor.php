<?php

declare(strict_types=1);

namespace App\State\Task;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * createTask / updateTask: по умолчанию назначает задачу на автора и проверяет, что исполнитель,
 * колонка и родительская задача относятся к тому же проекту.
 *
 * @implements ProcessorInterface<Task, Task>
 */
final readonly class TaskWriteProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<Task, Task> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Task
    {
        /** @var Task $data */
        $project = $data->getProject();

        if ($data->getAssignedTo() === null) {
            /** @var User $user */
            $user = $this->security->getUser();
            $data->setAssignedTo($user);
        }

        if (!$project->isMember($data->getAssignedTo())) {
            throw new BadRequestHttpException('Исполнитель не является участником проекта');
        }

        if ($data->getKanbanColumn() !== null && !$data->getKanbanColumn()->getProject()->getId()->equals($project->getId())) {
            throw new BadRequestHttpException('Колонка относится к другому проекту');
        }

        if ($data->getParent() !== null && !$data->getParent()->getProject()->getId()->equals($project->getId())) {
            throw new BadRequestHttpException('Родительская задача относится к другому проекту');
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
