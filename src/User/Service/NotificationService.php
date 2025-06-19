<?php

declare(strict_types=1);

namespace App\User\Service;

use App\Order\Entity\OrderItem;
use App\Order\Enum\DeliveryType;
use App\Order\Enum\OrderNotificationType;
use App\Order\Message\OrderNotificationItemMessage;
use App\Order\Message\OrderNotificationMessage;
use App\User\Controller\Request\UserRegistrationRequest;
use App\User\Enum\NotificationType;
use App\User\Message\UserRegistrationEmailNotificationMessage;
use App\User\Message\UserRegistrationSmsNotificationMessage;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class NotificationService
{
    public function __construct(private MessageBusInterface $messageBus) {}

    /**
     * @throws ExceptionInterface
     */
    public function sendRegistrationNotification(UserRegistrationRequest $dto): void
    {
        if ($dto->phone) {
            $this->messageBus->dispatch(new UserRegistrationSmsNotificationMessage($dto->phone));
        } else {
            $this->messageBus->dispatch(new UserRegistrationEmailNotificationMessage($dto->email));
        }
    }

    public function sendOrderNotification(
        NotificationType $type,
        string $userEmail,
        OrderNotificationType $notificationType,
        int $orderNum,
        Collection $orderItems,
        DeliveryType $deliveryType,
        ?int $kladrId,
        ?string $fullAddress,
    ): void {
        $orderItemMessages = array_map(
            static fn(OrderItem $item) => new OrderNotificationItemMessage(
                $item->getProduct()->getName(),
                $item->getPrice(),
                $item->getProduct()->getDescription(),
            ),
            $orderItems->toArray(),
        );

        $message = new OrderNotificationMessage(
            $type,
            $userEmail,
            $notificationType,
            $orderNum,
            $orderItemMessages,
            $deliveryType,
            $kladrId,
            $fullAddress,
        );

        $this->messageBus->dispatch($message);
    }
}
