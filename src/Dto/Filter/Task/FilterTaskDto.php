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
        private readonly ?string $projectId = null,
        private readonly ?string $status = null,
        private readonly ?string $text = null,
    ) {

    }

    public function getStatus(): ?TaskStatusEnum
    {
        return $this->status ? TaskStatusEnum::tryFrom($this->status) : null;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    public static function fromArray(array $data): static
    {
        return new static(
            projectId: self::parseNullableString($data['project_id']),
            status: self::parseNullableString($data['status']),
            text: self::parseNullableString($data['text']),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->getStatus()?->value,
            'text' => $this->getText(),
            'project_id' => $this->getProjectId(),
        ];
    }
}
