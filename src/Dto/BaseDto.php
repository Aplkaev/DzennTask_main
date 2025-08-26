<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;

abstract class BaseDto implements BaseFromArrayDto, BaseFromModalDto, BaseJsonSerializeDto, \JsonSerializable
{
}
