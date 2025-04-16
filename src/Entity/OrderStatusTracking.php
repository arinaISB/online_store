<?php

namespace App\Entity;

use App\Enum\OrderStatus;
use App\Repository\OrderStatusTrackingRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderStatusTrackingRepository::class)]
#[ORM\Table(name: 'order_status_tracking')]
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

    #[ORM\Column(length: 20)]
    private OrderStatus $oldStatus;

    #[ORM\Column(length: 20)]
    private OrderStatus $newStatus;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    public function __construct(Order $order, User $createdBy, OrderStatus $oldStatus, OrderStatus $newStatus)
    {
        $this->order = $order;
        $this->createdBy = $createdBy;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOldStatus(): OrderStatus
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

    public function setOldStatus(OrderStatus $oldStatus): static
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
