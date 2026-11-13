<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver\User;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Мутация registerUser: хэширует пароль и сразу выдаёт JWT в поле token.
 * Сохраняет пользователя стандартный persist-процессор API Platform (после валидации).
 */
final readonly class RegisterResolver implements MutationResolverInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager,
    ) {
    }

    /**
     * @param User $item
     */
    public function __invoke(?object $item, array $context): User
    {
        $email = mb_strtolower(trim($item->getEmail()));

        if ($this->userRepository->findOneByEmail($email) !== null) {
            throw new BadRequestHttpException('Пользователь с таким email уже существует');
        }

        $item->setEmail($email);
        $item->setPassword($this->passwordHasher->hashPassword($item, (string) $item->getPlainPassword()));
        $item->setToken($this->jwtManager->create($item));

        return $item;
    }
}
