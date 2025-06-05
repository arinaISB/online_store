<?php

declare(strict_types=1);

namespace App\Product\Controller\Request;

use Symfony\Component\DependencyInjection\Attribute\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

#[Exclude]
final readonly class ProductRequest
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Valid]
    public Measurements $measurements;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $cost;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    public int $tax;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $version;

    public ?string $description;

    public function __construct(
        string $name,
        Measurements $measurements,
        int $cost,
        int $tax,
        int $version,
        ?string $description,
    ) {
        $this->name = $name;
        $this->measurements = $measurements;
        $this->cost = $cost;
        $this->tax = $tax;
        $this->version = $version;
        $this->description = $description;
    }
}
