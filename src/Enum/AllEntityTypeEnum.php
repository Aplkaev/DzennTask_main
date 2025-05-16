<?php

namespace App\Enum;

use App\Entity\Task;
use App\Entity\Project;

enum AllEntityTypeEnum: string{
    case PROJECT = Project::class;
    case TASK = Task::class;
}