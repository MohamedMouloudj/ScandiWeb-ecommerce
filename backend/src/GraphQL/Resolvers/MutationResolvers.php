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

        $this->em->beginTransaction();

        try {
            $order = new Order();
            $order->setTotalAmount($input['totalAmount']);
            $currency = $this->em->getRepository(\App\Entity\Currency::class)
                ->findOneBy(['label' => $input['currency']]);
            if (!$currency) {
                throw new \Exception('Currency not found: ' . $input['currency']);
            }
            $order->setCurrencyEntity($currency);

            $this->em->persist($order);
            $this->em->flush(); // --> order ID

            // Create order items
            foreach ($input['items'] as $itemInput) {
                // error_log('DEBUG MutationResolvers: itemInput = ' . print_r($itemInput['selectedAttributes'], true));
                $product = $this->findEntityOrThrow(
                    Product::class,
                    $itemInput['productId'],
                    'Product not found'
                );

                // Attribute selection verification
                foreach ($itemInput['selectedAttributes'] as $selection) {
                    $attributeSetId = $selection['attributeSetId'];
                    $selectedValue = $selection['selectedValue'];

                    // 1. Check attribute set is assigned to product
                    $assigned = false;
                    foreach ($product->getProductAttributes() as $pa) {
                        if ($pa->getAttributeSet()->getId() === $attributeSetId) {
                            $assigned = true;
                            break;
                        }
                    }
                    if (!$assigned) {
                        throw new \Exception(
                            "Attribute set $attributeSetId is not assigned to product {$product->getId()}"
                        );
                    }

                    // 2. Check selectedValue exists in attributes for that set
                    $attribute = $this->em->getRepository(\App\Entity\Attribute::class)
                        ->findOneBy(['id' => $selectedValue, 'attributeSet' => $attributeSetId]);
                    if (!$attribute) {
                        throw new \Exception(
                            "Selected attribute $selectedValue is not valid for set $attributeSetId"
                        );
                    }
                }

                $orderItem = new OrderItem();
                $orderItem->setOrder($order);
                $orderItem->setProduct($product);
                $orderItem->setQuantity($itemInput['quantity']);
                $orderItem->setSelectedAttributes($itemInput['selectedAttributes'] ?? []);

                $this->em->persist($orderItem);
            }

            $this->em->flush();
            $this->em->commit();

            // Cache the new order
            $this->loaderManager->orders()->prime('orders', $order->getId(), $order);

            return $order;
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}
