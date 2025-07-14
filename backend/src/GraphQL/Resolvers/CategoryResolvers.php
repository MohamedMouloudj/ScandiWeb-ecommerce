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
        $promise = $this->loaderManager->categories()->loadProductsByCategory($category->getId());
        return $this->loaderManager->await($promise);
    }
}
