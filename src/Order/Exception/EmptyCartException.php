<?php

declare(strict_types=1);

namespace App\Order\Exception;

final class EmptyCartException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Cannot create order: cart is empty');
    }
}
