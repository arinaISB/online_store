<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['persist', 'remove'])]
    private Collection $orderItems;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderStatusTracking::class, cascade: ['persist', 'remove'])]
    private Collection $statusTracking;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $notificationType; // todo enum

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $totalCost;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $deliveryAddress;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private string $deliveryType; // todo enum

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $kladrId;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private DateTimeInterface $updatedAt;

    public function __construct(
        User $user,
        int $totalCost,
        string $deliveryType,
        ?string $notificationType = null,
        ?string $deliveryAddress = null,
        ?int $kladrId = null
    ) {
        $this->user = $user;
        $this->totalCost = $totalCost;
        $this->deliveryType = $deliveryType;
        $this->notificationType = $notificationType;
        $this->deliveryAddress = $deliveryAddress;
        $this->kladrId = $kladrId;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->orderItems = new ArrayCollection();
        $this->statusTracking = new ArrayCollection();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNotificationType(): ?string
    {
        return $this->notificationType;
    }

    public function setNotificationType(?string $notificationType): self
    {
        $this->notificationType = $notificationType;

        return $this;
    }

    public function getTotalCost(): int
    {
        return $this->totalCost;
    }

    public function setTotalCost(int $totalCost): self
    {
        $this->totalCost = $totalCost;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(string $deliveryType): self
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    public function getKladrId(): ?int
    {
        return $this->kladrId;
    }

    public function setKladrId(?int $kladrId): self
    {
        $this->kladrId = $kladrId;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
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
