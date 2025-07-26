<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product_attributes')]
class ProductAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productAttributes')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: AttributeSet::class)]
    #[ORM\JoinColumn(name: 'attribute_set_id', referencedColumnName: 'id')]
    private AttributeSet $attributeSet;

    // Getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getProduct(): Product
    {
        return $this->product;
    }
    public function getAttributeSet(): AttributeSet
    {
        return $this->attributeSet;
    }
}
