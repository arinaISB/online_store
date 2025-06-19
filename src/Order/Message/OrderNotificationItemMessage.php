<?php

declare(strict_types=1);

namespace App\Order\Message;

final readonly class OrderNotificationItemMessage
{
    public function __construct(
        public string $name,
        public int $cost,
        public ?string $additionalInfo = null,
    ) {}
}
