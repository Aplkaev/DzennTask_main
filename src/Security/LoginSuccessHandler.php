<?php

namespace App\Security;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

final readonly class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
    ) {
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
    ): ?JsonResponse {
        /** @var User $user */
        $user = $token->getUser();

        return new JsonResponse([
            'token' => $this->jwtManager->create($user),
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'telegram' => $user->getTelegram(),
                'phone' => $user->getPhone(),
                'timezone' => $user->getTimezone(),
                'avatarUrl' => $user->getAvatarUrl(),
            ],
        ]);
    }
}
