<?php

declare(strict_types=1);

namespace App\Order\Controller;

use App\Order\Controller\Request\CreateOrderRequest;
use App\Order\Controller\Request\UpdateOrderStatus;
use App\Order\Entity\Order;
use App\Order\Service\OrderService;
use App\User\Entity\User;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
final readonly class OrderController
{
    public function __construct(
        private OrderService $orderService,
    ) {}

    #[Route('orders', methods: [Request::METHOD_POST])]
    #[IsGranted('ROLE_USER')]
    public function create(#[MapRequestPayload] CreateOrderRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $this->orderService->createOrder(
            $user,
            $request,
        );

        return new JsonResponse('Order created', Response::HTTP_CREATED);
    }

    #[Route('orders/{id}', methods: [Request::METHOD_PATCH])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateStatus(
        #[MapEntity(id: 'id')]
        Order $order,
        #[CurrentUser]
        User $user,
        #[MapRequestPayload]
        UpdateOrderStatus $request,
    ): JsonResponse {
        $this->orderService->updateStatus(
            $order,
            $user,
            $request,
        );

        return new JsonResponse('Status updated', Response::HTTP_NO_CONTENT);
    }
}
