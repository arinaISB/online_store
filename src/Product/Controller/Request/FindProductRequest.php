<?php

declare(strict_types=1);

namespace App\Product\Controller\Request;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\DependencyInjection\Attribute\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

#[Exclude]
final readonly class FindProductRequest
{
    #[Assert\NotBlank]
    public string $search;

    #[Assert\Type(Types::INTEGER)]
    #[Assert\PositiveOrZero]
    public ?int $minCost;

    #[Assert\Type(Types::INTEGER)]
    #[Assert\PositiveOrZero]
    public ?int $maxCost;

    public function __construct(
        string $search,
        ?int $minCost = null,
        ?int $maxCost = null,
    ) {
        $this->search = $search;
        $this->minCost = $minCost;
        $this->maxCost = $maxCost;
    }
}
