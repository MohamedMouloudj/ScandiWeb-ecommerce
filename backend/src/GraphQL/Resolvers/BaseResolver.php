<?php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManagerInterface;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use React\Promise\PromiseInterface;

/**
 * Base Resolver with common functionality
 */
abstract class BaseResolver
{
    protected EntityManagerInterface $em;
    protected EcommerceDataLoaderManager $dataLoader;

    public function __construct(EntityManagerInterface $em, EcommerceDataLoaderManager $dataLoader)
    {
        $this->em = $em;
        $this->dataLoader = $dataLoader;
    }

    protected function resolvePromise(PromiseInterface $promise): mixed
    {
        return $this->dataLoader->await($promise);
    }

    protected function findEntityOrThrow(string $entityClass, $id, ?string $errorMessage = null): object
    {
        $entity = $this->em->getRepository($entityClass)->find($id);
        if (!$entity) {
            throw new \Exception($errorMessage ?? "{$entityClass} not found");
        }
        return $entity;
    }
}
