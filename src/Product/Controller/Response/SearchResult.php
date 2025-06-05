<?php

declare(strict_types=1);

namespace App\Product\Controller\Response;

use App\Product\Entity\Product;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

#[Exclude]
final readonly class SearchResult
{
    public Product $product;
    public float $score;
    public array $highlight;

    public function __construct(
        Product $product,
        float $score,
        array $highlight,
    ) {
        $this->product = $product;
        $this->score = $score;
        $this->highlight = $highlight;
    }
}
