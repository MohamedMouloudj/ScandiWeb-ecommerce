<?php

namespace App\Database\DataLoader;

use App\Entity\Order;
use App\Entity\OrderItem;
use React\Promise\PromiseInterface;

class OrderDataLoader extends BaseDataLoader
{
    protected function initializeLoaders(): void
    {
        // Load orders by IDs
        $this->createLoader('orders', function (array $orderIds): PromiseInterface {
            error_log("Loading orders: " . implode(', ', $orderIds) . "\n");

            $orders = $this->em->getRepository(Order::class)->findBy(['id' => $orderIds]);

            $orderMap = [];
            foreach ($orders as $order) {
                $orderMap[$order->getId()] = $order;
            }

            $result = array_map(fn($id) => $orderMap[$id] ?? null, $orderIds);
            return $this->promiseAdapter->createFulfilled($result);
        });

        // Load order items by order IDs
        $this->createLoader('orderItems', function (array $orderIds): PromiseInterface {
            // error_log("Loading order items for orders: " . implode(', ', $orderIds) . "\n");

            $orderItems = $this->em->getRepository(OrderItem::class)
                ->createQueryBuilder('oi')
                ->join('oi.order', 'o')
                ->where('o.id IN (:orderIds)')
                ->setParameter('orderIds', $orderIds)
                ->getQuery()
                ->getResult();

            $orderItemsByOrder = [];
            foreach ($orderIds as $orderId) {
                $orderItemsByOrder[$orderId] = [];
            }

            foreach ($orderItems as $orderItem) {
                $orderId = $orderItem->getOrder()->getId();
                if (isset($orderItemsByOrder[$orderId])) {
                    $orderItemsByOrder[$orderId][] = $orderItem;
                }
            }

            $result = array_map(fn($orderId) => $orderItemsByOrder[$orderId], $orderIds);
            return $this->promiseAdapter->createFulfilled($result);
        });
    }

    public function loadOrder(int $orderId): PromiseInterface
    {
        return $this->getLoader('orders')->load($orderId);
    }

    public function loadOrderItems(int $orderId): PromiseInterface
    {
        return $this->getLoader('orderItems')->load($orderId);
    }
}
