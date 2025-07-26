import type { Product } from "@/types/Product";
import { useLoaderData } from "react-router";
import { getPriceWithSymbol, stripHtml, toKebabCase } from "@/utils/helpers";
import GalleryCarousel from "@/components/ui/GalleryCarousel";
import SwatchAttribute from "@/components/ui/SwatchAttribute";
import TextAttribute from "@/components/ui/TextAttribute";
import type { SelectedAttribute } from "@/types/Order";
import { useState } from "react";
import useCart from "@/store/useCartStore";
import Error from "../ui/Error";

export default function ProductDetails() {
  const { product, error }: { product: Product; error: object } =
    useLoaderData();
  const { addItem } = useCart();
  const [selectedAttributes, setSelectedAttributes] = useState<
    SelectedAttribute[]
  >([]);
  const previewPrice = getPriceWithSymbol({
    prices: product.prices,
  });

  if (error) {
    return <Error error={error} />;
  }

  const handleAttributeSelect = (
    attributeSetId: string,
    attributeId: string
  ) => {
    setSelectedAttributes((prev) => {
      const index = prev.findIndex(
        (attr) => attr.attributeSetId === attributeSetId
      );
      if (index !== -1) {
        return prev.map((attr, i) =>
          i === index ? { ...attr, selectedValue: attributeId } : attr
        );
      } else {
        return [...prev, { attributeSetId, selectedValue: attributeId }];
      }
    });
  };

  const handleAddToCart = () => {
    addItem({
      product: product,
      quantity: 1,
      selectedAttributes: selectedAttributes,
    });
  };
  const sortedAttributes = [...(product.attributes ?? [])]?.sort((a, b) => {
    return a.name.localeCompare(b.name);
  });

  const isAddToCartDisabled = !product.inStock
    ? false
    : !sortedAttributes.length
    ? true
    : product.attributes?.length === selectedAttributes.length;

  return (
    <div className="flex flex-col md:flex-row gap-12 mt-8 w-full max-w-6xl mx-auto">
      <GalleryCarousel images={product.gallery} productName={product.name} />
      <div className="flex-1 flex flex-col gap-6 min-w-[300px]">
        <h1 className="text-2xl font-primary font-semibold text-neutral-black mb-2">
          {product.name}
        </h1>
        <div className="flex flex-col gap-6">
          {sortedAttributes?.map((attributeSet) => (
            <div key={attributeSet.id} className="flex flex-col gap-1">
              <div className="attribute-set-name">{attributeSet.name}:</div>
              <div
                className={`flex flex-wrap ${
                  attributeSet.type === "TEXT" ? "gap-4" : "gap-2"
                }`}
                data-testid={`product-attribute-${toKebabCase(
                  attributeSet.name
                )}`}
              >
                {attributeSet.items.map((item) => {
                  if (attributeSet.type === "TEXT") {
                    return (
                      <TextAttribute
                        key={item.id}
                        attributeSet={attributeSet}
                        attribute={item}
                        selected={
                          selectedAttributes.find(
                            (attr) => attr.attributeSetId === attributeSet.id
                          )?.selectedValue === item.id
                        }
                        onSelect={() => {
                          handleAttributeSelect(attributeSet.id, item.id);
                        }}
                      />
                    );
                  } else {
                    return (
                      <SwatchAttribute
                        key={item.id}
                        attributeSet={attributeSet}
                        attribute={item}
                        selected={
                          selectedAttributes.find(
                            (attr) => attr.attributeSetId === attributeSet.id
                          )?.selectedValue === item.id
                        }
                        onSelect={() => {
                          handleAttributeSelect(attributeSet.id, item.id);
                        }}
                      />
                    );
                  }
                })}
              </div>
            </div>
          ))}
          <div className="flex flex-col gap-2">
            <div className="attribute-set-name">PRICE:</div>
            <div className="text-xl font-bold text-neutral-black font-primary">
              {previewPrice}
            </div>
          </div>
          <button
            className={`${
              isAddToCartDisabled
                ? "bg-primary cursor-pointer hover:bg-primary/80 "
                : "bg-neutral-black/50 cursor-not-allowed"
            } text-white font-primary text-md font-semibold py-4 px-8 transition-colors duration-200 w-full `}
            data-testid="add-to-cart"
            disabled={!isAddToCartDisabled}
            onClick={() => {
              handleAddToCart();
            }}
          >
            ADD TO CART
          </button>
          <p
            className="text-neutral-black font-secondary text-md font-normal mt-2"
            data-testid="product-description"
          >
            {stripHtml(product.description)}
          </p>
        </div>
      </div>
    </div>
  );
}
