<?php

namespace App\GraphQL\Resolvers;

use App\Entity\AttributeSet;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use Doctrine\ORM\EntityManagerInterface;

class AttributeSetResolvers extends BaseResolver
{
    private EcommerceDataLoaderManager $loaderManager;

    public function __construct(EntityManagerInterface $em, EcommerceDataLoaderManager $loaderManager)
    {
        $this->em = $em;
        $this->loaderManager = $loaderManager;
    }

    /**
     * Resolve items (attributes) for an attribute set
     */
    public function resolveItems(AttributeSet $attributeSet): array
    {
        $promise = $this->loaderManager->attributes()->loadAttributes($attributeSet->getId());
        return $this->loaderManager->await($promise);
    }

    /**
     * Resolve type for an attribute set
     */
    public function resolveType(AttributeSet $attributeSet): string
    {
        return $attributeSet->getDisplayType();
    }
}
