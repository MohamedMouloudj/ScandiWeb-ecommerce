<?php

namespace App\Database\DataLoader;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use Overblog\PromiseAdapter\Adapter\ReactPromiseAdapter;
use Doctrine\ORM\EntityManagerInterface;
use React\Promise\PromiseInterface;

class EcommerceDataLoaderManager
{
    private EntityManagerInterface $em;
    private PromiseAdapterInterface $promiseAdapter;

    private CategoryDataLoader $categoryLoader;
    private ProductDataLoader $productLoader;
    private AttributeDataLoader $attributeLoader;
    private OrderDataLoader $orderLoader;

    public function __construct(EntityManagerInterface $em, ?PromiseAdapterInterface $promiseAdapter = null)
    {
        $this->em = $em;
        $this->promiseAdapter = $promiseAdapter ?? new ReactPromiseAdapter();
        $this->initializeLoaders();
    }

    private function initializeLoaders(): void
    {
        $this->categoryLoader = new CategoryDataLoader($this->em, $this->promiseAdapter);
        $this->productLoader = new ProductDataLoader($this->em, $this->promiseAdapter);
        $this->attributeLoader = new AttributeDataLoader($this->em, $this->promiseAdapter);
        $this->orderLoader = new OrderDataLoader($this->em, $this->promiseAdapter);
    }

    public function categories(): CategoryDataLoader
    {
        return $this->categoryLoader;
    }

    public function products(): ProductDataLoader
    {
        return $this->productLoader;
    }

    public function attributes(): AttributeDataLoader
    {
        return $this->attributeLoader;
    }

    public function orders(): OrderDataLoader
    {
        return $this->orderLoader;
    }

    public function clearAllCaches(): self
    {
        $this->categoryLoader->clearAllCaches();
        $this->productLoader->clearAllCaches();
        $this->attributeLoader->clearAllCaches();
        $this->orderLoader->clearAllCaches();
        return $this;
    }

    public function await(?PromiseInterface $promise = null): mixed
    {
        return DataLoader::await($promise);
    }
}
