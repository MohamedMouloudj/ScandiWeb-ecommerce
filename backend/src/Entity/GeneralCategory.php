<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class GeneralCategory extends Category
{
    public function getDisplayName(): string
    {
        return $this->name;
    }

    public function getSpecialProperties(): array
    {
        return ['type' => 'general'];
    }
}
