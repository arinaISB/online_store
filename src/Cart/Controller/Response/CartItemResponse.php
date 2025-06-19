<?php

declare(strict_types=1);

namespace App\Cart\Controller\Response;

final readonly class CartItemResponse
{
    public function __construct(
        public string $productName,
        public int $quantity,
        public int $cost,
        public int $subtotal,
    ) {}
}
