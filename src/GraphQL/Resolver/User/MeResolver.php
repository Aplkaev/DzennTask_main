<?php

namespace App\GraphQL\Resolver\User;

use ApiPlatform\GraphQl\Resolver\QueryItemResolverInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class MeResolver implements QueryItemResolverInterface
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function __invoke(?object $item, array $context): object
    {
        return $this->security->getUser();
    }
}
