<?php

declare(strict_types=1);

namespace App\Shared\Password;

class PasswordGenerator
{
    public static function generate(): string
    {
        $passwordLength = 2;
        $new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, $passwordLength);
        $new_password .= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $passwordLength);
        $new_password .= substr(str_shuffle('0123456789'), 0, $passwordLength);
        $new_password .= substr(str_shuffle('!@#$%^&*()-_=+~'), 0, $passwordLength);

        return str_shuffle($new_password);
    }
}
