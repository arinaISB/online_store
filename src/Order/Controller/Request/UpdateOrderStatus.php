<?php

declare(strict_types=1);

namespace App\Order\Controller\Request;

use App\Order\Enum\OrderStatus;
use Symfony\Component\DependencyInjection\Attribute\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

#[Exclude]
final readonly class UpdateOrderStatus
{
    #[Assert\NotBlank]
    #[Assert\Choice(callback: [self::class, 'getAllowedStatuses'])]
    public string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function getAllowedStatuses(): array
    {
        return array_map(static fn(OrderStatus $case) => $case->value, OrderStatus::cases());
    }
}
