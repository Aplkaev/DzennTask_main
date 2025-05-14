<?php

namespace App\UseCase\Crud;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProjectCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly ProjectRepository $projectRepository
    )
    {
        parent::__construct($em);
    }

    public function createEntityFromArray(array $data): mixed
    {

        $project = new Project();
        $project->setName($data['name']);
        $project->setDescription($data['description']);
        $project->setTag($data['tag']);

        $this->projectRepository->save($project);

        return $project;
    }
    public function updateEntityFromArray(mixed $project, array $data): mixed
    {
        if($project instanceof Project === false) { 
            throw new BadRequestException('Is not project item');
        }
        
        $project->setDescription($data['description']);
        $project->setTag($data['tag']);
        
        $this->projectRepository->save($project);

        return $project;
    }
}