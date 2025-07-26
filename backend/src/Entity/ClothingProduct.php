<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ClothingProduct extends Product
{
    public function canAddToCart(): bool
    {
        return $this->inStock && $this->hasRequiredAttributes();
    }

    public function getProductSpecificData(): array
    {
        return [
            'type' => 'clothing',
            'requiresSize' => true,
            'requiresColor' => true
        ];
    }

    private function hasRequiredAttributes(): bool
    {
        // Check if product has size and color attributes, not in the assignment
        return true;
    }
}
