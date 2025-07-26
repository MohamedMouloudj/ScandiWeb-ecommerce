<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class SizeAttribute extends Attribute
{
    public function getDisplayFormat(): string
    {
        return 'size';
    }

    public function isSelectable(): bool
    {
        return true;
    }
}
