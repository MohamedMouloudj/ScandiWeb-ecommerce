<?php

namespace App\GraphQL\Resolvers;

use App\Entity\AttributeSet;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use App\Entity\Attribute;
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
        $attributesBatch = $this->loaderManager->await($promise);
        $attributes = is_array($attributesBatch) && count($attributesBatch) === 1 && is_array($attributesBatch[0])
            ? $attributesBatch[0]
            : (is_array($attributesBatch) ? $attributesBatch : []);
        return $attributes;
    }

    public function resolveAttribute(Attribute $attribute): Attribute
    {
        return $attribute;
    }

    /**
     * Resolve type for an attribute set
     */
    public function resolveType(AttributeSet $attributeSet): string
    {
        return $attributeSet->getDisplayType();
    }

    /**
     * Resolvers for Attribute Entity
     */
    public function resolveAttributeId($attribute)
    {
        return $attribute->getId();
    }
    public function resolveAttributeDisplayValue($attribute)
    {
        return $attribute->getDisplayValue();
    }
    public function resolveAttributeValue($attribute)
    {
        return $attribute->getValue();
    }
}
