<?php

declare(strict_types=1);

namespace App\Cart\Controller\Request;

use Symfony\Component\DependencyInjection\Attribute\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

#[Exclude]
final readonly class CreateCartRequest
{
    #[Assert\Positive]
    public int $productId;

    #[Assert\Positive]
    public int $quantity;

    public function __construct(int $productId, int $quantity = 1)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
}
