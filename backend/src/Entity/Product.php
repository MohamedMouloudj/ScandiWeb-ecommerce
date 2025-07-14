<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'product_type', type: 'string')]
#[ORM\DiscriminatorMap(['clothing' => ClothingProduct::class, 'tech' => TechProduct::class, 'general' => GeneralProduct::class])]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    protected string $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $name;

    #[ORM\Column(name: 'in_stock', type: 'boolean')]
    protected bool $inStock = true;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    protected Category $category;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $brand = null;

    #[ORM\Column(name: 'price_amount', type: 'decimal', precision: 10, scale: 2)]
    protected float $priceAmount;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'price_currency_id', referencedColumnName: 'id')]
    protected ?Currency $priceCurrencyEntity = null;


    #[ORM\Column(name: 'created_at', type: 'datetime')]
    protected \DateTime $createdAt;

    #[ORM\OneToMany(targetEntity: ProductImage::class, mappedBy: 'product')]
    #[ORM\OrderBy(['sortOrder' => 'ASC'])]
    protected Collection $gallery;

    #[ORM\OneToMany(targetEntity: ProductAttribute::class, mappedBy: 'product')]
    protected Collection $productAttributes;

    public function __construct()
    {
        $this->gallery = new ArrayCollection();
        $this->productAttributes = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->priceAmount, 2);
    }

    public function canAddToCart(): bool
    {
        return $this->inStock;
    }

    public function getProductSpecificData(): array
    {
        return ['type' => 'general'];
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function isInStock(): bool
    {
        return $this->inStock;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function getCategory(): Category
    {
        return $this->category;
    }
    public function getBrand(): ?string
    {
        return $this->brand;
    }
    public function getPriceAmount(): float
    {
        return $this->priceAmount;
    }
    public function getPriceCurrencyEntity(): ?Currency
    {
        return $this->priceCurrencyEntity;
    }

    public function setPriceCurrencyEntity(?Currency $priceCurrencyEntity): self
    {
        $this->priceCurrencyEntity = $priceCurrencyEntity;
        return $this;
    }

    public function getGallery(): Collection
    {
        return $this->gallery;
    }
    public function getProductAttributes(): Collection
    {
        return $this->productAttributes;
    }
}

#[ORM\Entity]
class GeneralProduct extends Product
{
    public function getFormattedPrice(): string
    {
        return number_format($this->priceAmount, 2) . ' ' . $this->priceCurrencyEntity->getSymbol();
    }

    public function canAddToCart(): bool
    {
        return $this->inStock;
    }

    public function getProductSpecificData(): array
    {
        return [
            'type' => 'general'
        ];
    }
}

#[ORM\Entity]
class ClothingProduct extends Product
{
    public function getFormattedPrice(): string
    {
        return number_format($this->priceAmount, 2) . ' ' . $this->priceCurrencyEntity->getSymbol();
    }

    public function canAddToCart(): bool
    {
        return $this->inStock && $this->hasRequiredAttributes();
    }

    public function getProductSpecificData(): array
    {
        return [
            'type' => 'clothing',
            'requiresSize' => true,
            'requiresColor' => true
        ];
    }

    private function hasRequiredAttributes(): bool
    {
        // Check if product has size and color attributes
        return true; // Implement your logic
    }
}

#[ORM\Entity]
class TechProduct extends Product
{
    public function getFormattedPrice(): string
    {
        return $this->priceCurrencyEntity->getSymbol() . ' ' . number_format($this->priceAmount, 2);
    }

    public function canAddToCart(): bool
    {
        return $this->inStock; // Tech products don't need attribute selection
    }

    public function getProductSpecificData(): array
    {
        return [
            'type' => 'tech',
            'hasWarranty' => true,
            'requiresAttributes' => false
        ];
    }
}
