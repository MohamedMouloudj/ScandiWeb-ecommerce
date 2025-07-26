<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class GeneralProduct extends Product
{
    public function canAddToCart(): bool
    {
        return $this->inStock;
    }

    public function getProductSpecificData(): array
    {
        return [
            'type' => 'general'
        ];
    }
}
