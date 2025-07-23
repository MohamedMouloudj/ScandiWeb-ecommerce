<?php

namespace App\Database\DataLoader;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\AttributeSet;
use App\Entity\ProductAttribute;
use React\Promise\PromiseInterface;

class ProductDataLoader extends BaseDataLoader
{
    protected function initializeLoaders(): void
    {
        // Load products by IDs
        $this->createLoader('products', function (array $productIds): PromiseInterface {

            $products = $this->em->getRepository(Product::class)->findBy(['id' => $productIds]);

            // Initialize all product entities to avoid proxy issues
            foreach ($products as $product) {
                $this->em->initializeObject($product);
            }

            $productMap = [];
            foreach ($products as $product) {
                $productMap[$product->getId()] = $product;
            }

            $result = array_map(fn($id) => $productMap[$id] ?? null, $productIds);
            return $this->promiseAdapter->createFulfilled($result);
        });

        // Load product images by product IDs
        $this->createLoader('productImages', function (array $productIds): PromiseInterface {

            $images = $this->em->getRepository(ProductImage::class)
                ->createQueryBuilder('pi')
                ->select('pi', 'p')
                ->join('pi.product', 'p')
                ->where('p.id IN (:productIds)')
                ->orderBy('pi.sortOrder', 'ASC')
                ->setParameter('productIds', $productIds)
                ->getQuery()
                ->getResult();


            $validImages = [];
            foreach ($images as $image) {
                if ($image->getId() !== null) {
                    $validImages[] = $image;
                }
            }

            $imagesByProduct = [];
            foreach ($productIds as $productId) {
                $imagesByProduct[$productId] = [];
            }

            foreach ($validImages as $image) {
                $productId = $image->getProduct()->getId();
                if (isset($imagesByProduct[$productId])) {
                    $imagesByProduct[$productId][] = $image;
                }
            }

            $result = array_map(fn($productId) => $imagesByProduct[$productId], $productIds);
            return $this->promiseAdapter->createFulfilled($result);
        });

        $this->createLoader('attributeSets', function (array $productIds): PromiseInterface {
            $productAttributes = $this->em->getRepository(ProductAttribute::class)
                ->createQueryBuilder('pa')
                ->join('pa.product', 'p')
                ->join('pa.attributeSet', 'aset')
                ->where('p.id IN (:productIds)')
                ->setParameter('productIds', $productIds)
                ->getQuery()
                ->getResult();

            // Initialize all entities
            foreach ($productAttributes as $pa) {
                $this->em->initializeObject($pa);
                $this->em->initializeObject($pa->getProduct());
                $this->em->initializeObject($pa->getAttributeSet());
            }

            $attributeSetsByProduct = [];
            foreach ($productIds as $productId) {
                $attributeSetsByProduct[$productId] = [];
            }

            // Filter and group by product ID
            foreach ($productAttributes as $pa) {
                // error_log('DEBUG ProductDataLoader.attributeSets $pa: ' . $pa->getAttributeSet()->getId());
                $productId = $pa->getProduct()->getId();
                if (in_array($productId, $productIds)) {
                    $attributeSet = $pa->getAttributeSet();
                    if (!in_array($attributeSet, $attributeSetsByProduct[$productId])) {
                        $attributeSetsByProduct[$productId][] = $attributeSet;
                    }
                }
            }

            $result = array_map(fn($productId) => $attributeSetsByProduct[$productId], $productIds);
            return $this->promiseAdapter->createFulfilled($result);
        });

        $this->createLoader('productPrices', function (array $productIds): PromiseInterface {
            $qb = $this->em->getRepository(\App\Entity\ProductPrice::class)
                ->createQueryBuilder('pp')
                ->join('pp.product', 'p')
                ->where('p.id IN (:productIds)')
                ->setParameter('productIds', $productIds);

            $prices = $qb->getQuery()->getResult();

            $pricesByProduct = [];
            foreach ($productIds as $productId) {
                $pricesByProduct[$productId] = [];
            }
            foreach ($prices as $price) {
                $productId = $price->getProduct()->getId();
                if (isset($pricesByProduct[$productId])) {
                    $pricesByProduct[$productId][] = $price;
                }
            }
            $result = array_map(fn($productId) => $pricesByProduct[$productId], $productIds);
            return $this->promiseAdapter->createFulfilled($result);
        });
    }

    public function loadProduct(string $productId): PromiseInterface
    {
        return $this->getLoader('products')->load($productId);
    }

    public function loadProducts(array $productIds)
    {
        return $this->getLoader('products')->loadMany($productIds);
    }

    public function loadProductImages(string $productId): PromiseInterface
    {
        return $this->getLoader('productImages')->load($productId);
    }

    public function loadAttributeSets(string $productId): PromiseInterface
    {
        return $this->getLoader('attributeSets')->load($productId);
    }

    public function loadProductPrices(string $productId): PromiseInterface
    {
        return $this->getLoader('productPrices')->load($productId);
    }
}
