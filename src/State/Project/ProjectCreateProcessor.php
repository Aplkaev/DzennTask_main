<?php

declare(strict_types=1);

namespace App\State\Project;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * createProject: создатель проекта сразу становится его владельцем (ROLE_OWNER),
 * иначе проект не попадёт ни в одну выборку — доступ к проектам идёт через ProjectUser.
 *
 * @implements ProcessorInterface<Project, Project>
 */
final readonly class ProjectCreateProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<Project, Project> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Project
    {
        /** @var Project $data */
        /** @var User $user */
        $user = $this->security->getUser();

        $owner = new ProjectUser();
        $owner->setUser($user);
        $owner->setRole(RoleEnum::ROLE_OWNER);
        $data->addProjectUser($owner);

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
