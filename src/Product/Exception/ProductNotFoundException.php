<?php

declare(strict_types=1);

namespace App\Product\Exception;

final class ProductNotFoundException extends \RuntimeException
{
    public static function withId(int $id): self
    {
        return new self(\sprintf('Product with id "%s" not found.', $id));
    }
}
