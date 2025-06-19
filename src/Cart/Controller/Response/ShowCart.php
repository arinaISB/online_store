<?php

declare(strict_types=1);

namespace App\Cart\Controller\Response;

final readonly class ShowCart
{
    /**
     * @param CartItemResponse[] $items
     * @param int $total
     */
    public function __construct(
        public array $items,
        public int $total,
    ) {}
}
