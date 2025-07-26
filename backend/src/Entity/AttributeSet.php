<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'attribute_sets')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'attribute_type', type: 'string')]
#[ORM\DiscriminatorMap(['text' => TextAttributeSet::class, 'swatch' => SwatchAttributeSet::class])]
abstract class AttributeSet
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    protected string $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $name;

    #[ORM\Column(type: 'string', length: 50)]
    protected string $type;

    #[ORM\OneToMany(targetEntity: Attribute::class, mappedBy: 'attributeSet')]
    protected Collection $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    abstract public function validateValue(string $value): bool;
    abstract public function getDisplayType(): string;

    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }
}

#[ORM\Entity]
class TextAttributeSet extends AttributeSet
{

    public function validateValue(string $value): bool
    {
        return !empty(trim($value));
    }

    public function getDisplayType(): string
    {
        return 'TEXT';
    }
}

#[ORM\Entity]
class SwatchAttributeSet extends AttributeSet
{


    public function validateValue(string $value): bool
    {
        return $this->attributes->exists(fn($key, $attr) => $attr->getValue() === $value);
    }

    public function getDisplayType(): string
    {
        return 'SWATCH';
    }
}
