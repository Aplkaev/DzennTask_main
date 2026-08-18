<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\ProjectUser;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    public const VIEW   = 'TASK_VIEW';
    public const CREATE = 'TASK_CREATE';
    public const EDIT   = 'TASK_EDIT';
    public const DELETE = 'TASK_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::CREATE, self::EDIT, self::DELETE], true)
            && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        $projectUser = $this->findProjectUser($task, $user);
        if ($projectUser === null) {
            return false;
        }

        // Создание задачи доступно любому участнику проекта — ограничение "видеть/управлять
        // всеми задачами" касается уже существующих задач других людей, не создания своей.
        if (self::CREATE === $attribute) {
            return true;
        }

        if (RoleEnum::ROLE_OWNER === $projectUser->getRole()) {
            return true;
        }

        return $task->getAssignedTo()?->getId() === $user->getId();
    }

    private function findProjectUser(Task $task, User $user): ?ProjectUser
    {
        foreach ($task->getProject()->getProjectUsers() as $projectUser) {
            if ($projectUser->getUser()->getId() === $user->getId()) {
                return $projectUser;
            }
        }

        return null;
    }
}
