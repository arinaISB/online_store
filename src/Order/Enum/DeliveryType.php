<?php

declare(strict_types=1);

namespace App\Order\Enum;

enum DeliveryType: string
{
    case SELFDELIVERY = 'SELFDELIVERY';
    case COURIER = 'COURIER';
}
