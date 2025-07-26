<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ColorAttribute extends Attribute
{
    public function getDisplayFormat(): string
    {
        return 'color';
    }

    public function isSelectable(): bool
    {
        return true;
    }
}
