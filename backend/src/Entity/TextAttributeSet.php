<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
