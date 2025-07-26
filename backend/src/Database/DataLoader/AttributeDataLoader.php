<?php

namespace App\Database\DataLoader;

use App\Entity\Attribute;
use React\Promise\PromiseInterface;

class AttributeDataLoader extends BaseDataLoader
{
    protected function initializeLoaders(): void
    {
        // Load attributes by attribute set IDs
        $this->createLoader('attributes', function (array $attributeSetIds): PromiseInterface {
            // error_log('DEBUG AttributeDataLoader: attributeSetIds = ' . json_encode(value: $attributeSetIds));

            $attributes = $this->em->getRepository(Attribute::class)
                ->createQueryBuilder('a')
                ->join('a.attributeSet', 'aset')
                ->where('aset.id IN (:attributeSetIds)')
                ->setParameter('attributeSetIds', $attributeSetIds)
                ->getQuery()
                ->getResult();


            $attributesByAttributeSet = [];
            foreach ($attributeSetIds as $attributeSetId) {
                $attributesByAttributeSet[$attributeSetId] = [];
            }

            foreach ($attributes as $attribute) {
                $attributeSetId = $attribute->getAttributeSet()->getId();
                if (isset($attributesByAttributeSet[$attributeSetId])) {
                    $attributesByAttributeSet[$attributeSetId][] = $attribute;
                }
            }

            $result = array_map(fn($attributeSetId) => $attributesByAttributeSet[$attributeSetId], $attributeSetIds);
            return $this->promiseAdapter->createFulfilled($result);
        });
    }

    /**
     * Loads attributes for a attribute set
     */
    public function loadAttributes(string $attributeSetId): PromiseInterface
    {
        return $this->getLoader('attributes')->load($attributeSetId);
    }
}
