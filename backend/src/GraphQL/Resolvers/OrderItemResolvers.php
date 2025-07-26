<?php

namespace App\GraphQL\Resolvers;

use App\Entity\OrderItem;
use App\Entity\Product;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use Doctrine\ORM\EntityManagerInterface;

class OrderItemResolvers extends BaseResolver
{
    private EcommerceDataLoaderManager $loaderManager;

    public function __construct(EntityManagerInterface $em, EcommerceDataLoaderManager $loaderManager)
    {
        $this->em = $em;
        $this->loaderManager = $loaderManager;
    }

    /**
     * Resolve product for an order item
     */
    public function resolveProduct(OrderItem $orderItem): ?Product
    {
        $productId = $orderItem->getProduct()->getId();
        $promise = $this->loaderManager->products()->loadProduct($productId);
        return $this->loaderManager->await($promise);
    }

    /**
     * Resolve selected attributes for an order item
     */
    public function resolveSelectedAttributes(OrderItem $orderItem): array
    {
        $selectedAttributes = $orderItem->getSelectedAttributes();


        $result = [];
        foreach ($selectedAttributes as $attr) {
            $result[] = [
                'attributeSetId' => $attr['attributeSetId'],
                'selectedValue' => $attr['selectedValue']
            ];
        }
        return $result;
    }
}
