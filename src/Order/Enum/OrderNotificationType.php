<?php

declare(strict_types=1);

namespace App\Order\Enum;

enum OrderNotificationType: string
{
    case REQUIRES_PAYMENT = 'REQUIRES_PAYMENT';
    case SUCCESS_PAYMENT = 'SUCCESS_PAYMENT';
    case COMPLETED = 'COMPLETED';
}
