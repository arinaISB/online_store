<?php

declare(strict_types=1);

namespace App\Product\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ProductRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $name,
        #[Assert\NotBlank]
        #[Assert\Valid]
        public Measurements $measurements,
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public int $cost,
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\PositiveOrZero]
        public int $tax,
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public int $version,
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public ?int $id,
        #[Assert\Type('string')]
        public ?string $description = null,
    ) {
    }
}
