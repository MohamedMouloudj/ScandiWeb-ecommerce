<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

// ========== CATEGORIES ==========

#[ORM\Entity]
#[ORM\Table(name: 'categories')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['clothing' => ClothingCategory::class, 'tech' => TechCategory::class, 'general' => GeneralCategory::class])]
abstract class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    protected string $name;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    protected \DateTime $createdAt;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    protected Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    abstract public function getDisplayName(): string;
    abstract public function getSpecialProperties(): array;

    // Getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    public function getProducts(): Collection
    {
        return $this->products;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}

#[ORM\Entity]
class ClothingCategory extends Category
{
    public function getDisplayName(): string
    {
        return ucfirst($this->name) . ' Clothing';
    }

    public function getSpecialProperties(): array
    {
        return ['hasSeasons' => true, 'hasSizes' => true];
    }
}

#[ORM\Entity]
class TechCategory extends Category
{
    public function getDisplayName(): string
    {
        return $this->name . ' Tech';
    }

    public function getSpecialProperties(): array
    {
        return ['hasWarranty' => true, 'hasSpecs' => true];
    }
}

#[ORM\Entity]
class GeneralCategory extends Category
{
    public function getDisplayName(): string
    {
        return $this->name;
    }

    public function getSpecialProperties(): array
    {
        return [];
    }
}
