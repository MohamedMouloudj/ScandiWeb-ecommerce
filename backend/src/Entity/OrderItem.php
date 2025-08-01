<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_items')]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id')]
    private Order $order;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product $product;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 1;

    #[ORM\Column(name: 'selected_attributes', type: 'json', nullable: true)]
    private array $selectedAttributes = [];

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSelectedAttributes(): array
    {
        return $this->selectedAttributes;
    }

    // Setters
    public function setOrder(Order $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setSelectedAttributes(array $selectedAttributes): self
    {
        $this->selectedAttributes = $selectedAttributes;
        return $this;
    }
}
