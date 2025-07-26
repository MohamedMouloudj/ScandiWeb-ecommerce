import { useState } from "react";
import { NavLink } from "react-router";
import { getPriceWithSymbol, getThumbnail, toKebabCase } from "@/utils/helpers";
import useCart from "@/store/useCartStore";
import type { Product } from "@/types/Product";

interface ProductCardProps {
  product: Product;
}

export default function ProductCard({ product }: ProductCardProps) {
  const { addItem } = useCart();
  const [isHovered, setIsHovered] = useState<boolean>(false);
  const thumbnail = getThumbnail(product.gallery);
  const previewPrice = getPriceWithSymbol({
    prices: product.prices,
    quantity: 1,
  });
  return (
    <NavLink
      className="relative product-card"
      data-testid={`product-${toKebabCase(product.name)}`}
      to={`/product/${product.id}`}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
    >
      <div className="relative w-full">
        <img
          src={thumbnail.imageUrl}
          alt={product.name}
          className={`product-card-image ${
            !product.inStock ? "grayscale opacity-60" : ""
          }`}
        />
        {!product.inStock && (
          <span className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-neutral-gray text-xl font-primary font-normal z-10 pointer-events-none w-max">
            OUT OF STOCK
          </span>
        )}

        <div
          className={`absolute bottom-0 right-8 w-12 h-12 rounded-full bg-primary flex-center z-10 shadow-lg transition-all duration-300 ${
            isHovered && product.inStock
              ? "opacity-100 visible"
              : "opacity-0 invisible"
          }`}
          onClick={(e) => {
            e.preventDefault();
            e.stopPropagation();
            addItem({
              product: product,
              quantity: 1,
              selectedAttributes: [],
            });
          }}
        >
          <img
            src="/images/EmptyCartLight.svg"
            alt="add to cart"
            className="w-1/2 h-1/2"
          />
        </div>
      </div>
      <h3 className="product-card-name">{product.name}</h3>
      <span
        className={`product-card-price ${
          product.inStock ? "text-neutral-black" : "text-neutral-gray"
        }`}
      >
        {previewPrice}
      </span>
    </NavLink>
  );
}
