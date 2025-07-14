<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'attributes')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'attr_type', type: 'string')]
#[ORM\DiscriminatorMap(['text' => TextAttribute::class, 'color' => ColorAttribute::class, 'size' => SizeAttribute::class])]
abstract class Attribute
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    protected string $id;

    #[ORM\ManyToOne(targetEntity: AttributeSet::class, inversedBy: 'attributes')]
    #[ORM\JoinColumn(name: 'attribute_set_id', referencedColumnName: 'id')]
    protected AttributeSet $attributeSet;

    #[ORM\Column(name: 'display_value', type: 'string', length: 255)]
    protected string $displayValue;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $value;

    abstract public function getDisplayFormat(): string;
    abstract public function isSelectable(): bool;

    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    public function getAttributeSet(): AttributeSet
    {
        return $this->attributeSet;
    }
    public function getDisplayValue(): string
    {
        return $this->displayValue;
    }
    public function getValue(): string
    {
        return $this->value;
    }
}

#[ORM\Entity]
class TextAttribute extends Attribute
{
    public function getDisplayFormat(): string
    {
        return 'text';
    }

    public function isSelectable(): bool
    {
        return true;
    }
}

#[ORM\Entity]
class ColorAttribute extends Attribute
{
    public function getDisplayFormat(): string
    {
        return 'color-swatch';
    }

    public function isSelectable(): bool
    {
        return true;
    }

    public function getColorHex(): string
    {
        return $this->value; // Assuming value contains hex color
    }
}

#[ORM\Entity]
class SizeAttribute extends Attribute
{
    public function getDisplayFormat(): string
    {
        return 'size-button';
    }

    public function isSelectable(): bool
    {
        return true;
    }

    public function getSizeCategory(): string
    {
        // XS, S, M, L, XL or 40, 41, 42, etc.
        return is_numeric($this->value) ? 'numeric' : 'text';
    }
}
