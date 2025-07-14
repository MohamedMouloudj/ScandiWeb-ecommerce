<?php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManagerInterface;
use App\Database\DataLoader\EcommerceDataLoaderManager;

class ResolverManager
{
    private EntityManagerInterface $em;
    private EcommerceDataLoaderManager $loaderManager;

    private QueryResolvers $queryResolvers;
    private CategoryResolvers $categoryResolvers;
    private ProductResolvers $productResolvers;
    private AttributeSetResolvers $attributeSetResolvers;
    private OrderResolvers $orderResolvers;
    private OrderItemResolvers $orderItemResolvers;
    private MutationResolvers $mutationResolvers;
    private ProductImageResolvers $productImageResolvers;
    public function __construct(EntityManagerInterface $em, EcommerceDataLoaderManager $loaderManager)
    {
        $this->em = $em;
        $this->loaderManager = $loaderManager;

        $this->initializeResolvers();
    }

    private function initializeResolvers(): void
    {
        $this->queryResolvers = new QueryResolvers($this->em, $this->loaderManager);
        $this->categoryResolvers = new CategoryResolvers($this->em, $this->loaderManager);
        $this->productResolvers = new ProductResolvers($this->em, $this->loaderManager);
        $this->attributeSetResolvers = new AttributeSetResolvers($this->em, $this->loaderManager);
        $this->orderResolvers = new OrderResolvers($this->em, $this->loaderManager);
        $this->orderItemResolvers = new OrderItemResolvers($this->em, $this->loaderManager);
        $this->mutationResolvers = new MutationResolvers($this->em, $this->loaderManager);
        $this->productImageResolvers = new ProductImageResolvers($this->em, $this->loaderManager);
    }

    public function getResolverMap(): array
    {
        return [
            'Query' => [
                'categories' => [$this->queryResolvers, 'getCategories'],
                'products' => [$this->queryResolvers, 'getProducts'],
                'product' => [$this->queryResolvers, 'getProduct'],
            ],
            'Mutation' => [
                'placeOrder' => [$this->mutationResolvers, 'placeOrder'],
            ],
            'Category' => [
                'products' => [$this->categoryResolvers, 'resolveProducts'],
                // I removed scalar field resolvers - let GraphQL handle them automatically
            ],
            'Product' => [
                'category' => [$this->productResolvers, 'resolveCategory'],
                'gallery' => [$this->productResolvers, 'resolveGallery'],
                'attributes' => [$this->productResolvers, 'resolveAttributes'],
                'price' => [$this->productResolvers, 'resolvePrice'],
            ],
            'AttributeSet' => [
                'items' => [$this->attributeSetResolvers, 'resolveItems'],
                'type' => [$this->attributeSetResolvers, 'resolveType'],
            ],
            'Order' => [
                'items' => [$this->orderResolvers, 'resolveItems'],
                'currency' => [$this->orderResolvers, 'resolveCurrency'],
            ],
            'OrderItem' => [
                'product' => [$this->orderItemResolvers, 'resolveProduct'],
                'selectedAttributes' => [$this->orderItemResolvers, 'resolveSelectedAttributes'],
            ],
            'ProductImage' => [
                'id' => [$this->productImageResolvers, 'resolveId'],
                'imageUrl' => [$this->productImageResolvers, 'resolveImageUrl'],
                'sortOrder' => [$this->productImageResolvers, 'resolveSortOrder'],
            ],
        ];
    }

    public function query(): QueryResolvers
    {
        return $this->queryResolvers;
    }

    public function mutation(): MutationResolvers
    {
        return $this->mutationResolvers;
    }

    public function category(): CategoryResolvers
    {
        return $this->categoryResolvers;
    }

    public function product(): ProductResolvers
    {
        return $this->productResolvers;
    }

    public function attributeSet(): AttributeSetResolvers
    {
        return $this->attributeSetResolvers;
    }

    public function order(): OrderResolvers
    {
        return $this->orderResolvers;
    }

    public function orderItem(): OrderItemResolvers
    {
        return $this->orderItemResolvers;
    }

    public function productImage(): ProductImageResolvers
    {
        return $this->productImageResolvers;
    }
}
