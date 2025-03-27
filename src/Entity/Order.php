<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order')]
    private Collection $orderItems;

    #[ORM\OneToMany(targetEntity: OrderStatusTracking::class, mappedBy: 'order')]
    private Collection $statusTracking;

    #[ORM\Column(length: 255)]
    private string $notificationType;

    #[ORM\Column]
    private int $totalCost;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deliveryAddress;

    #[ORM\Column(length: 255)]
    private string $deliveryType;

    #[ORM\Column(nullable: true)]
    private ?int $kladrId;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    private DateTimeImmutable $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    public function setNotificationType(string $notificationType): static
    {
        $this->notificationType = $notificationType;

        return $this;
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

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(string $deliveryType): static
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
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


    public function getStatusTracking(): Collection
    {
        return $this->statusTracking;
    }
}
