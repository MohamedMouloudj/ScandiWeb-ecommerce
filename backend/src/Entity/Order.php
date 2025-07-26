<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'total_amount', type: 'decimal', precision: 10, scale: 2)]
    private float $totalAmount;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'id')]
    private ?Currency $currencyEntity = null;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['remove'], orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getCurrencyEntity(): ?Currency
    {
        return $this->currencyEntity;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    // Setters
    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function setCurrencyEntity(?Currency $currencyEntity): self
    {
        $this->currencyEntity = $currencyEntity;
        return $this;
    }
}
