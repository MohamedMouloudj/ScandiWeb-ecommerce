<?php

namespace App\GraphQL\Resolvers;

use App\Entity\Category;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use Doctrine\ORM\EntityManagerInterface;

class CategoryResolvers extends BaseResolver
{
    private EcommerceDataLoaderManager $loaderManager;

    public function __construct(EntityManagerInterface $em, EcommerceDataLoaderManager $loaderManager)
    {
        $this->em = $em;
        $this->loaderManager = $loaderManager;
    }

    /**
     * Resolve products for a category
     */
    public function resolveProducts(Category $category): array
    {
        // If the category name is 'all', then we need to load all products
        if (strtolower($category->getName()) === 'all') {
            $promise = $this->loaderManager->products()->loadAllProducts();
            $allProductsArr = $this->loaderManager->await($promise);
            return $allProductsArr;
        }
        $promise = $this->loaderManager->categories()->loadProductsByCategory($category->getId());
        return $this->loaderManager->await($promise);
    }
}
