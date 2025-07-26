<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
