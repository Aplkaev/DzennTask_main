<?php

declare(strict_types=1);

namespace App\Enum\Task;

enum TaskStatusEnum: string
{
    case DONE = 'done';
    case NEW = 'new';
}
