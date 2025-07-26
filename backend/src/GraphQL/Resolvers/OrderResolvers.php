<?php

namespace App\GraphQL\Resolvers;

use App\Entity\Order;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use Doctrine\ORM\EntityManagerInterface;

class OrderResolvers extends BaseResolver
{
    private EcommerceDataLoaderManager $loaderManager;

    public function __construct(EntityManagerInterface $em, EcommerceDataLoaderManager $loaderManager)
    {
        $this->em = $em;
        $this->loaderManager = $loaderManager;
    }

    /**
     * Resolve items for an order
     */
    public function resolveItems(Order $order): array
    {
        $promise = $this->loaderManager->orders()->loadOrderItems($order->getId());
        return $this->loaderManager->await($promise);
    }

    /**
     * Resolve currency for an order
     */
    public function resolveCurrency(Order $order): string
    {
        return $order->getCurrencyEntity()?->getSymbol() ?? 'USD';
    }
}
