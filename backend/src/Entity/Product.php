<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'product_type', type: 'string')]
#[ORM\DiscriminatorMap([
    'clothing' => ClothingProduct::class,
    'tech' => TechProduct::class,
    'general' => GeneralProduct::class
])]
abstract class Product
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

    #[ORM\OneToMany(targetEntity: ProductPrice::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    private Collection $prices;


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
        $this->prices = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getFormattedPrice(ProductPrice $price): string
    {
        return number_format($price->getAmount(), 2) . ' ' . $price->getCurrency()->getSymbol();
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
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function getGallery(): Collection
    {
        return $this->gallery;
    }
    public function getProductAttributes(): Collection
    {
        return $this->productAttributes;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    // Setters
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function setInStock(bool $inStock): self
    {
        $this->inStock = $inStock;
        return $this;
    }
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }
    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
