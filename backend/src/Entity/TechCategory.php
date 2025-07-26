<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TechCategory extends Category
{
    public function getDisplayName(): string
    {
        return $this->name . ' Tech';
    }

    public function getSpecialProperties(): array
    {
        return ['type' => 'tech'];
    }
}
