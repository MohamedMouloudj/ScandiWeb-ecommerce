<?php

namespace App\GraphQL\Resolvers;

use App\Entity\ProductImage;

class ProductImageResolvers extends BaseResolver
{
    /**
     * Resolve ID for a ProductImage
     */
    public function resolveId(ProductImage $productImage): int
    {
        return $productImage->getId();
    }

    /**
     * Resolve imageUrl for a ProductImage
     */
    public function resolveImageUrl(ProductImage $productImage): string
    {
        return $productImage->getImageUrl();
    }

    /**
     * Resolve sortOrder for a ProductImage
     */
    public function resolveSortOrder(ProductImage $productImage): int
    {
        return $productImage->getSortOrder();
    }
}
