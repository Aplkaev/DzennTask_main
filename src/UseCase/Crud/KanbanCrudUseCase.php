<?php

declare(strict_types=1);

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Dto\KanbanDto;
use App\Entity\KanbanColumn;
use App\Repository\KanbanColumnRepository;
use App\Repository\ProjectRepository;
use App\UseCase\Project\VerifyUserAccessToProjectUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class KanbanCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly KanbanColumnRepository $kanbanColumnRepository,
        protected readonly Security $security,
        protected readonly ProjectRepository $projectRepository,
        protected readonly VerifyUserAccessToProjectUseCase $verifyUserAccessToProjectUseCase,
    ) {
        parent::__construct($em);
    }

    /**
     * @param KanbanDto $dto
     */
    public function createEntityFromArray(BaseDto|KanbanDto $dto): KanbanColumn
    {
        $this->verifyUserAccessToProjectUseCase->execute($dto->projectId);

        $project = $this->projectRepository->find($dto->projectId);

        $kanban = new KanbanColumn();
        $kanban->setName($dto->name);
        $kanban->setProject($project);
        $kanban->setColor($dto->color);
        $kanban->setPosition($dto->position);
        $this->kanbanColumnRepository->save($kanban);

        return $kanban;
    }

    /**
     * @param KanbanColumn $kanban
     * @param KanbanDto    $dto
     */
    public function updateEntityFromArray(mixed $kanban, BaseDto|KanbanDto $dto): KanbanColumn
    {
        $this->verifyUserAccessToProjectUseCase->execute($dto->projectId);

        $kanban->setName($dto->name);
        $kanban->setColor($dto->color);
        $kanban->setPosition($dto->position);
        $this->kanbanColumnRepository->save($kanban);

        return $kanban;
    }
}
