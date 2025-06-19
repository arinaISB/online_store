<?php

declare(strict_types=1);

namespace App\Order\Controller\Request;

use App\Order\Enum\DeliveryType;
use Symfony\Component\DependencyInjection\Attribute\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

#[Exclude]
final readonly class CreateOrderRequest
{
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [DeliveryType::SELFDELIVERY->value, DeliveryType::COURIER->value])]
    public string $deliveryType;

    #[Assert\When(
        expression: 'this.isCourierDelivery()',
        constraints: [
            new Assert\NotBlank(),
        ],
    )]
    public ?string $deliveryAddress;

    #[Assert\When(
        expression: 'this.isCourierDelivery()',
        constraints: [
            new Assert\NotNull(),
        ],
    )]
    public ?int $kladrId;

    public function __construct(string $deliveryType, ?string $deliveryAddress = null, ?int $kladrId = null)
    {
        $this->deliveryType = $deliveryType;
        $this->deliveryAddress = $deliveryAddress;
        $this->kladrId = $kladrId;
    }

    public function isCourierDelivery(): bool
    {
        return $this->deliveryType === DeliveryType::COURIER->value;
    }
}
