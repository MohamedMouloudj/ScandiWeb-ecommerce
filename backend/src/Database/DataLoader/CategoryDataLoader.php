<?php

namespace App\Database\DataLoader;

use App\Entity\Category;
use App\Entity\Product;
use React\Promise\PromiseInterface;

class CategoryDataLoader extends BaseDataLoader
{
    protected function initializeLoaders(): void
    {
        // Load categories by IDs
        $this->createLoader('categories', function (array $categoryIds): PromiseInterface {
            echo "Loading categories: " . implode(', ', $categoryIds) . "\n";

            $categories = $this->em->getRepository(Category::class)->findBy(['id' => $categoryIds]);

            $categoryMap = [];
            foreach ($categories as $category) {
                $categoryMap[$category->getId()] = $category;
            }

            $result = array_map(fn($id) => $categoryMap[$id] ?? null, $categoryIds);
            return $this->promiseAdapter->createFulfilled($result);
        });

        // Load products by category IDs
        $this->createLoader('productsByCategory', function (array $categoryIds): PromiseInterface {
            echo "Loading products for categories: " . implode(', ', $categoryIds) . "\n";

            $products = $this->em->getRepository(Product::class)
                ->createQueryBuilder('p')
                ->join('p.category', 'c')
                ->where('c.id IN (:categoryIds)')
                ->setParameter('categoryIds', $categoryIds)
                ->getQuery()
                ->getResult();

            $productsByCategory = [];
            foreach ($categoryIds as $categoryId) {
                $productsByCategory[$categoryId] = [];
            }

            foreach ($products as $product) {
                $categoryId = $product->getCategory()->getId();
                if (isset($productsByCategory[$categoryId])) {
                    $productsByCategory[$categoryId][] = $product;
                }
            }

            $result = array_map(fn($categoryId) => $productsByCategory[$categoryId], $categoryIds);
            return $this->promiseAdapter->createFulfilled($result);
        });
    }

    public function loadCategory(int $categoryId): PromiseInterface
    {
        return $this->getLoader('categories')->load($categoryId);
    }

    public function loadProductsByCategory(int $categoryId): PromiseInterface
    {
        return $this->getLoader('productsByCategory')->load($categoryId);
    }

    public function loadCategories(array $categoryIds)
    {
        return $this->getLoader('categories')->loadMany($categoryIds);
    }
}
