<?php

namespace App\GraphQL\Resolvers;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\OrderItem;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use Doctrine\ORM\EntityManagerInterface;

class MutationResolvers extends BaseResolver
{
    private EcommerceDataLoaderManager $loaderManager;

    public function __construct(EntityManagerInterface $em, EcommerceDataLoaderManager $loaderManager)
    {
        $this->em = $em;
        $this->loaderManager = $loaderManager;
    }

    /**
     * Place an order
     */
    public function placeOrder($root, $args): Order
    {
        $input = $args['input'];

        // Start transaction
        $this->em->beginTransaction();

        try {
            // Create order
            $order = new Order();
            $order->setTotalAmount($input['totalAmount']);
            $order->setCurrencyEntity($input['currencyEntity']); // Fixed method name

            $this->em->persist($order);
            $this->em->flush(); // Flush to get order ID

            // Create order items
            foreach ($input['items'] as $itemInput) {
                $product = $this->findEntityOrThrow(Product::class, $itemInput['productId'], 'Product not found');

                $orderItem = new OrderItem();
                $orderItem->setOrder($order);
                $orderItem->setProduct($product);
                $orderItem->setQuantity($itemInput['quantity']);
                $orderItem->setSelectedAttributes($itemInput['selectedAttributes'] ?? []);

                $this->em->persist($orderItem);
            }

            $this->em->flush();
            $this->em->commit();

            // Prime caches with the new order
            $this->loaderManager->orders()->prime('orders', $order->getId(), $order);

            return $order;
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}
