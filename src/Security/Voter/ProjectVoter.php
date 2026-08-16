<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectVoter extends Voter
{
    public const VIEW   = 'PROJECT_VIEW';
    public const EDIT   = 'PROJECT_EDIT';
    public const DELETE = 'PROJECT_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var Project $project */
        $project = $subject;

        if ($user->getRoles() && in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        foreach ($project->getProjectUsers() as $projectUser) {
            if ($projectUser->getUser()->getId() === $user->getId()) {

                return true;
            }
        }

        return false;
    }
}
