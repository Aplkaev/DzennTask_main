<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
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

        if ($user->getRoles() && in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        foreach ($task->getProject()->getProjectUsers() as $projectUser) {
            if ($projectUser->getUser()->getId() === $user->getId()) {
                return true;
            }
        }

        return false;
    }
}
