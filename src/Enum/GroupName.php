<?php

declare(strict_types=1);

namespace App\Enum;

enum GroupName: string
{
    case CUSTOMER = 'customer';
    case ADMIN = 'admin';
    case SUPERADMIN = 'superadmin';

    public function toRole(): string
    {
        return match ($this) {
            self::CUSTOMER => 'ROLE_USER',
            self::ADMIN => 'ROLE_ADMIN',
            self::SUPERADMIN => 'ROLE_SUPER_ADMIN',
        };
    }
}
