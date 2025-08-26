<?php

declare(strict_types=1);

namespace App\Dto\Filter\Task;

use App\Dto\BaseFromArrayDto;
use App\Dto\BaseJsonSerializeDto;
use App\Enum\Task\TaskStatusEnum;
use App\Shared\Parser\ParseDataTrait;

final class FilterTaskDto implements BaseFromArrayDto, BaseJsonSerializeDto
{
    use ParseDataTrait;

    public function __construct(
        private readonly ?string $status = null,
    ) {

    }

    public function getStatus(): ?TaskStatusEnum
    {
        return $this->status ? TaskStatusEnum::tryFrom($this->status) : null;
    }

    public static function fromArray(array $data): static
    {
        return new static(
            status: self::parseNullableString($data['status'])
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->getStatus()?->value,
        ];
    }
}
