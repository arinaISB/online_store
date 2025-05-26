<?php

declare(strict_types=1);

namespace App\User\Enum;

enum NotificationType: string
{
    case SMS = 'SMS';
    case EMAIL = 'EMAIL';
}
