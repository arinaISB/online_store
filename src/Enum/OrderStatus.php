<?php

namespace App\Enum;

enum OrderStatus: string
{
    case PAID_AWAITING_ASSEMBLY = 'paid_and_awaiting_assembly';
    case IN_ASSEMBLY = 'in_assembly';
    case READY_TO_SHIP = 'ready_to_ship';
    case BEING_DELIVERED = 'being_delivered';
    case RECEIVED = 'received';
    case CANCELED = 'canceled';
}
