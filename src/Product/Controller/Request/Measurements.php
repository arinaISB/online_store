<?php

declare(strict_types=1);

namespace App\Product\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Measurements
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public int $weight,
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public int $height,
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public int $width,
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public int $length,
    ) {
    }
}
