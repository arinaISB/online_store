<?php

declare(strict_types=1);

namespace App\Product\Controller\Request;

use Symfony\Component\DependencyInjection\Attribute\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

#[Exclude]
final readonly class Measurements
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $weight;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $height;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $width;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $length;

    public function __construct(
        int $weight,
        int $height,
        int $width,
        int $length,
    ) {
        $this->weight = $weight;
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }
}
