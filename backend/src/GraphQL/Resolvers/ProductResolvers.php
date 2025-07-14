<?php

namespace App\GraphQL\Resolvers;

use App\Entity\Category;
use App\Entity\Product;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use App\Entity\ProductAttribute;
use App\Entity\ProductImage;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use Exception;

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
     * Resolve attributes for a product
     */
    public function resolveAttributes(Product $product): array
    {
        $promise = $this->loaderManager->products()->loadAttributeSets($product->getId());
        return $this->loaderManager->await($promise);
    }

    /**
     * Resolve price for a product
     */
    public function resolvePrice(Product $product): array
    {
        return [
            'amount' => $product->getPriceAmount(),
            'currency' => $product->getPriceCurrencyEntity()?->getSymbol() ?? 'USD'
        ];
    }
}
