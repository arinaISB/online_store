<?php

declare(strict_types=1);

namespace App\Order\Entity;

use App\Order\Enum\DeliveryType;
use App\Order\Repository\OrderRepository;
use App\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders', schema: 'sale')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class)]
    private Collection $orderItems;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderStatusTracking::class)]
    private Collection $statusTracking;

    #[ORM\Column(type: Types::INTEGER)]
    private int $totalCost;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $deliveryAddress;

    #[ORM\Column(length: 255, enumType: DeliveryType::class)]
    private DeliveryType $deliveryType;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $kladrId;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        User $user,
        int $totalCost,
        DeliveryType $deliveryType,
        ?string $deliveryAddress = null,
        ?int $kladrId = null,
        \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        \DateTimeImmutable $updatedAt = new \DateTimeImmutable(),
    ) {
        $this->user = $user;
        $this->totalCost = $totalCost;
        $this->deliveryType = $deliveryType;
        $this->deliveryAddress = $deliveryAddress;
        $this->kladrId = $kladrId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->orderItems = new ArrayCollection();
        $this->statusTracking = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTotalCost(): int
    {
        return $this->totalCost;
    }

    public function setTotalCost(int $totalCost): static
    {
        $this->totalCost = $totalCost;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?string $deliveryAddress): static
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getDeliveryType(): DeliveryType
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(DeliveryType $deliveryType): static
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    public function getKladrId(): ?int
    {
        return $this->kladrId;
    }

    public function setKladrId(?int $kladrId): static
    {
        $this->kladrId = $kladrId;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $item): void
    {
        $this->orderItems->add($item);
    }

    public function getStatusTracking(): Collection
    {
        return $this->statusTracking;
    }

    public function addStatusTracking(OrderStatusTracking $tracking): void
    {
        $this->statusTracking->add($tracking);
    }
}
