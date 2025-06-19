<?php

declare(strict_types=1);

namespace App\Order\Message;

use App\Order\Enum\DeliveryType;
use App\Order\Enum\OrderNotificationType;
use App\User\Enum\NotificationType;

final readonly class OrderNotificationMessage
{
    /**
     * @param OrderNotificationItemMessage[] $orderItems
     */
    public function __construct(
        public NotificationType $type,
        public string $userEmail,
        public OrderNotificationType $notificationType,
        public int $orderNum,
        public array $orderItems,
        public DeliveryType $deliveryType,
        public ?int $kladrId,
        public ?string $fullAddress,
    ) {}
}
