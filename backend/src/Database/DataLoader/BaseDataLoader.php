<?php

namespace App\Database\DataLoader;

use Overblog\DataLoader\DataLoader;
use Overblog\DataLoader\Option;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use Overblog\PromiseAdapter\Adapter\ReactPromiseAdapter;
use Doctrine\ORM\EntityManagerInterface;
use React\Promise\PromiseInterface;

/**
 * Base DataLoader with common functionality
 */
abstract class BaseDataLoader
{
    protected EntityManagerInterface $em;
    protected PromiseAdapterInterface $promiseAdapter;
    protected array $loaders = [];

    public function __construct(EntityManagerInterface $em, ?PromiseAdapterInterface $promiseAdapter = null)
    {
        $this->em = $em;
        $this->promiseAdapter = $promiseAdapter ?? new ReactPromiseAdapter();
        $this->initializeLoaders();
    }

    abstract protected function initializeLoaders(): void;

    protected function createLoader(string $name, callable $batchLoadFn, array $options = []): DataLoader
    {
        $defaultOptions = [
            'cache' => true,
            'maxBatchSize' => 100,
            'cacheKeyFn' => fn($key) => is_array($key) ? json_encode($key) : (string)$key
        ];

        $options = array_merge($defaultOptions, $options);

        $this->loaders[$name] = new DataLoader(
            $batchLoadFn,
            $this->promiseAdapter,
            new Option($options)
        );

        return $this->loaders[$name];
    }

    protected function getLoader(string $name): DataLoader
    {
        if (!isset($this->loaders[$name])) {
            throw new \InvalidArgumentException("Loader '{$name}' not found");
        }
        return $this->loaders[$name];
    }

    public function clearCache(string $loaderName, $key): self
    {
        $this->getLoader($loaderName)->clear($key);
        return $this;
    }

    public function clearAllCaches(): self
    {
        foreach ($this->loaders as $loader) {
            $loader->clearAll();
        }
        return $this;
    }

    public function prime(string $loaderName, $key, $value): self
    {
        $this->getLoader($loaderName)->prime($key, $value);
        return $this;
    }

    public function await(?PromiseInterface $promise = null): mixed
    {
        return DataLoader::await($promise);
    }
}
