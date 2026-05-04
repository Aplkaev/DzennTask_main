<?php

namespace App\Enum;

enum AllEntityTypeEnum: string
{
    case POST = 'post';
    case COMMENT = 'comment';
    case USER = 'user';
}
