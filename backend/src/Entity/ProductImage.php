<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product_gallery')]
class ProductImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'gallery')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product $product;

    #[ORM\Column(name: 'image_url', type: 'text')]
    private string $imageUrl;

    #[ORM\Column(name: 'sort_order', type: 'integer')]
    private int $sortOrder = 0;

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    // Setters (add these if they're missing)
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * Debug method to check what's being serialized
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'imageUrl' => $this->getImageUrl(),
            'sortOrder' => $this->getSortOrder()
        ];
    }

    /**
     * Alternative getter names that some GraphQL libraries might expect
     */
    public function id(): int
    {
        return $this->getId();
    }

    public function imageUrl(): string
    {
        return $this->getImageUrl();
    }

    public function sortOrder(): int
    {
        return $this->getSortOrder();
    }
}
