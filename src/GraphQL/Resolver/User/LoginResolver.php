<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver\User;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Мутация loginUser: проверяет email/пароль и возвращает пользователя с JWT в поле token.
 * Ничего не пишет в БД (write: false у операции).
 */
final readonly class LoginResolver implements MutationResolverInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager,
    ) {
    }

    /**
     * @param User|null $item
     */
    public function __invoke(?object $item, array $context): User
    {
        $user = $item instanceof User ? $this->userRepository->findOneByEmail($item->getEmail()) : null;

        if ($user === null || !$this->passwordHasher->isPasswordValid($user, (string) $item->getPlainPassword())) {
            throw new UnauthorizedHttpException('Bearer', 'Неверный email или пароль');
        }

        $user->setToken($this->jwtManager->create($user));

        return $user;
    }
}
