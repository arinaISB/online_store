<?php

declare(strict_types=1);

namespace App\Order\Service;

use App\Cart\Entity\CartItem;
use App\Order\Controller\Request\CreateOrderRequest;
use App\Order\Controller\Request\UpdateOrderStatus;
use App\Order\Entity\Order;
use App\Order\Entity\OrderItem;
use App\Order\Entity\OrderStatusTracking;
use App\Order\Enum\DeliveryType;
use App\Order\Enum\OrderNotificationType;
use App\Order\Enum\OrderStatus;
use App\Order\Exception\EmptyCartException;
use App\User\Entity\User;
use App\User\Enum\NotificationType;
use App\User\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private NotificationService $notificationService,
    ) {}

    public function createOrder(User $user, CreateOrderRequest $request): void
    {
        $cart = $user->getCart();
        $cartItems = $cart->getCartItems();

        if ($cartItems->isEmpty()) {
            throw new EmptyCartException();
        }

        $deliveryType = DeliveryType::tryFrom($request->deliveryType);
        $deliveryAddress = $request->deliveryAddress;
        $kladrId = $request->kladrId;

        $totalCost = 0;
        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {
            $totalCost += $cartItem->getQuantity() * $cartItem->getProduct()->getCost();
        }

        $order = new Order(
            $user,
            $totalCost,
            $deliveryType,
            $deliveryAddress,
            $kladrId,
        );
        $this->entityManager->persist($order);

        foreach ($cartItems as $cartItem) {
            $orderItem = new OrderItem(
                $order,
                $cartItem->getProduct(),
                $cartItem->getProduct()->getCost(),
                $cartItem->getQuantity(),
            );

            $order->addOrderItem($orderItem);
            $this->entityManager->persist($orderItem);
            $this->entityManager->remove($cartItem);
        }

        $orderStatusTracking = new OrderStatusTracking(
            $order,
            $user,
            OrderStatus::PAID_AWAITING_ASSEMBLY,
        );
        $order->addStatusTracking($orderStatusTracking);
        $this->entityManager->persist($orderStatusTracking);

        $this->entityManager->flush();

        $this->notificationService->sendOrderNotification(
            NotificationType::EMAIL,
            $user->getEmail(),
            OrderNotificationType::REQUIRES_PAYMENT,
            $order->getId(),
            $order->getOrderItems(),
            $deliveryType,
            $kladrId,
            $deliveryAddress,
        );
    }

    public function updateStatus(Order $order, User $user, UpdateOrderStatus $request): void
    {
        $lastStatusTracking = $order->getStatusTracking()->last();
        $oldStatus = $lastStatusTracking instanceof OrderStatusTracking
            ? $lastStatusTracking->getNewStatus()
            : null;

        $statusTracking = new OrderStatusTracking(
            $order,
            $user,
            OrderStatus::tryFrom($request->status),
            $oldStatus,
        );

        $order->addStatusTracking($statusTracking);

        $this->entityManager->persist($statusTracking);
        $this->entityManager->flush();
    }
}
