<?php

declare(strict_types=1);

namespace App\Order\Enum;

enum OrderStatus: string
{
    case PAID_AWAITING_ASSEMBLY = 'PAID_AWAITING_ASSEMBLY';
    case IN_ASSEMBLY = 'IN_ASSEMBLY';
    case READY_TO_SHIP = 'READY_TO_SHIP';
    case BEING_DELIVERED = 'BEING_DELIVERED';
    case RECEIVED = 'RECEIVED';
    case CANCELED = 'CANCELED';
}
