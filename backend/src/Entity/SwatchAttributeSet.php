<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
