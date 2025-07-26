<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ClothingCategory extends Category
{
    public function getDisplayName(): string
    {
        return ucfirst($this->name) . ' Clothing';
    }

    public function getSpecialProperties(): array
    {
        return ['type' => 'clothing'];
    }
}
