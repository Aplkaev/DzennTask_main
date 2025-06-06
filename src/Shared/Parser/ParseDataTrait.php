<?php

declare(strict_types=1);

namespace App\Shared\Parser;

trait ParseDataTrait
{
    use ParseArrayTrait;
    use ParseBoolTrait;
    use ParseFloatTrait;
    use ParseIntTrait;
    use ParseStringTrait;
    use ParseDtoTrait;
    use ParseDateTimeImmutableTrait;
}
