<?php

namespace App\GraphQL\Resolvers;

use App\Entity\Category;
use App\Entity\Product;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use Doctrine\ORM\EntityManagerInterface;

class ProductResolvers extends BaseResolver
{
    private EcommerceDataLoaderManager $loaderManager;

    public function __construct(EntityManagerInterface $em, EcommerceDataLoaderManager $loaderManager)
    {
        $this->em = $em;
        $this->loaderManager = $loaderManager;
    }

    /**
     * Resolve category for a product
     */
    public function resolveCategory(Product $product): ?Category
    {
        $categoryId = $product->getCategory()->getId();
        $promise = $this->loaderManager->categories()->loadCategory($categoryId);
        return $this->loaderManager->await($promise);
    }

    /**
     * Resolve gallery images for a product
     */
    public function resolveGallery(Product $product): array
    {
        $promise = $this->loaderManager->products()->loadProductImages($product->getId());
        $images = $this->loaderManager->await($promise);

        return $images;
    }

    /**
     * Resolve attribute sets for a product
     */
    public function resolveAttributes(Product $product): array
    {
        $promise = $this->loaderManager->products()->loadAttributeSets($product->getId());
        return $this->loaderManager->await($promise);
    }

    /**
     * Resolve prices for a product
     */
    public function resolvePrices(Product $product): array
    {
        $promise = $this->loaderManager->products()->loadProductPrices($product->getId());
        $prices = $this->loaderManager->await($promise);

        // This will map ProductPrice entities to the expected GraphQL format
        return array_map(function ($price) {
            return [
                'amount' => $price->getAmount(),
                'currency' => [
                    'label' => $price->getCurrency()->getLabel(),
                    'symbol' => $price->getCurrency()->getSymbol(),
                ]
            ];
        }, $prices);
    }
}
