<?php

declare(strict_types=1);

namespace App\Dto;

abstract class BaseDto implements BaseFromArrayDto, BaseFromModalDto, BaseJsonSerializeDto, \JsonSerializable
{
}
