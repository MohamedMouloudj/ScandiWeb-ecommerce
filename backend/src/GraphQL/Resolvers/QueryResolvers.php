<?php

namespace App\GraphQL\Resolvers;

use App\Entity\Category;
use App\Entity\Product;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use Doctrine\ORM\EntityManagerInterface;

class QueryResolvers extends BaseResolver
{
    private EcommerceDataLoaderManager $loaderManager;

    public function __construct(EntityManagerInterface $em, EcommerceDataLoaderManager $loaderManager)
    {
        $this->em = $em;
        $this->loaderManager = $loaderManager;
    }

    /**
     * Get all categories
     */
    public function getCategories(): array
    {
        $categories = $this->em->getRepository(Category::class)->findAll();

        // Prime the cache with loaded categories
        foreach ($categories as $category) {
            $this->em->initializeObject($category);
            $this->loaderManager->categories()->prime('categories', $category->getId(), $category);
        }

        return $categories;
    }

    /**
     * Get products, optionally filtered by category
     */
    public function getProducts($root, $args): array
    {
        $qb = $this->em->getRepository(Product::class)->createQueryBuilder('p');

        if (isset($args['categoryId'])) {
            $category = $this->em->getRepository(Category::class)->find($args['categoryId']);
            $qb->join('p.category', 'c')
                ->where('c.id = :categoryId')
                ->setParameter('categoryId', $args['categoryId']);
        } elseif (isset($args['categoryName']) && $args['categoryName'] !== null && $args['categoryName'] !== 'all') {
            $qb->join('p.category', 'c')
                ->where('c.name = :categoryName')
                ->setParameter('categoryName', $args['categoryName']);
        }
        // If categoryName is 'all' or null, return all products (no filter)

        $products = $qb->getQuery()->getResult();

        // Prime the cache with loaded products
        foreach ($products as $product) {
            $this->loaderManager->products()->prime('products', $product->getId(), $product);
        }

        return $products;
    }

    /**
     * Get a single product by ID
     */
    public function getProduct($root, $args): ?Product
    {
        $promise = $this->loaderManager->products()->loadProduct($args['id']);
        return $this->loaderManager->await($promise);
    }
}
