<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'currencies')]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $label;

    #[ORM\Column(type: 'string', length: 10)]
    private string $symbol;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'currencyEntity')]
    private Collection $orders;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'priceCurrencyEntity')]
    private Collection $products;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    // Setters
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;
        return $this;
    }
}
