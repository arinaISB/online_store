<?php

declare(strict_types=1);

namespace App\Order\Entity;

use App\Order\Enum\OrderStatus;
use App\Order\Repository\OrderStatusTrackingRepository;
use App\User\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderStatusTrackingRepository::class)]
#[ORM\Table(name: 'order_status_tracking', schema: 'tracking')]
class OrderStatusTracking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'id')]
    private User $createdBy;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'statusTracking')]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id')]
    private Order $order;

    #[ORM\Column(length: 30, nullable: true, enumType: OrderStatus::class)]
    private ?OrderStatus $oldStatus;

    #[ORM\Column(length: 30, enumType: OrderStatus::class)]
    private OrderStatus $newStatus;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        Order $order,
        User $createdBy,
        OrderStatus $newStatus,
        ?OrderStatus $oldStatus = null,
        \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {
        $this->order = $order;
        $this->createdBy = $createdBy;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOldStatus(): ?OrderStatus
    {
        return $this->oldStatus;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function setOldStatus(?OrderStatus $oldStatus): ?static
    {
        $this->oldStatus = $oldStatus;

        return $this;
    }

    public function getNewStatus(): OrderStatus
    {
        return $this->newStatus;
    }

    public function setNewStatus(OrderStatus $newStatus): static
    {
        $this->newStatus = $newStatus;

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
}
