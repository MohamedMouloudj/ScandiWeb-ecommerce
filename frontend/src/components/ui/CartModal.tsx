import SwatchAttribute from "./SwatchAttribute";
import TextAttribute from "./TextAttribute";
import {
  CURRENCY_LABEL,
  getPriceWithSymbol,
  getThumbnail,
  toKebabCase,
} from "@/utils/helpers";
import { MinusIcon, PlusIcon } from "lucide-react";
import { useFetcher, useActionData } from "react-router";
import useCart from "@/store/useCartStore";
import Spinner from "@/components/ui/Spinner";
import { useEffect, useState } from "react";
import Error from "./Error";

export default function CartModal() {
  const { isOpen, items, updateItemQuantity, clearCart } = useCart();
  const [error, setError] = useState<object | null>(null);
  const fetcher = useFetcher();
  const actionData = useActionData();

  useEffect(() => {
    if (actionData?.error || fetcher.data?.error) {
      setError(actionData?.error || fetcher.data?.error);
      return;
    }
    clearCart();
    setError(null);
  }, [actionData, fetcher.data]);

  if (!isOpen) return null;

  const totalPrice = getPriceWithSymbol(
    items.map((item) => ({
      prices: item.product.prices,
      quantity: item.quantity,
    }))
  );
  const handlePlaceOrder = () => {
    const orderData = {
      items: items.map((item) => ({
        productId: item.product.id,
        quantity: item.quantity,
        selectedAttributes: item.selectedAttributes,
      })),
      totalAmount: Number(totalPrice.replace(/[^\d.]/g, "")),
      currency: CURRENCY_LABEL,
    };

    fetcher.submit(
      { orderData: JSON.stringify(orderData) },
      { method: "post" }
    );
  };

  return (
    <div
      className="absolute top-full md:right-8 right-0 z-50 bg-background shadow-2xl px-4 py-8 flex flex-col gap-8 min-w-80"
      style={{ pointerEvents: "auto" }}
    >
      {fetcher.state === "submitting" ? (
        <div className="flex-center h-full">
          <Spinner size={12} />
        </div>
      ) : error ? (
        <Error error={error} setError={setError} />
      ) : (
        <>
          <div className="flex flex-col gap-8 ">
            <h2 className="font-primary text-md text-neutral-black mb-4">
              <span className="font-bold">My Bag, </span>
              <span className="font-medium">
                {items.length} {items.length === 1 ? "Item" : "Items"}
              </span>
            </h2>
            <div className="flex flex-col gap-10 md:max-h-[40vh] max-h-[50vh] overflow-y-auto">
              {items.map((item, idx) => {
                const price = getPriceWithSymbol({
                  prices: item.product.prices,
                  quantity: item.quantity,
                });
                return (
                  <div key={idx} className="flex gap-2 items-stretch max-w-2xs">
                    <div className="flex gap-1 justify-between w-full">
                      <div className="flex flex-col gap-2">
                        <div className="flex flex-col gap-2">
                          <h3 className="font-primary font-light text-lg text-neutral-black">
                            {item.product.name}
                          </h3>
                          <div className="font-primary font-bold text-md text-neutral-black">
                            {price}
                          </div>
                        </div>
                        {item.product.attributes?.map((attrSet) => (
                          <div key={attrSet.id} className="flex flex-col gap-1">
                            <div className="text-sm font-normal text-neutral-black font-primary">
                              {attrSet.name}:
                            </div>
                            <div
                              className="flex gap-2 p-1 flex-wrap"
                              data-testid={`cart-item-attribute-${toKebabCase(
                                attrSet.name
                              )}`}
                            >
                              {attrSet.type === "TEXT"
                                ? attrSet.items.map((attr) => (
                                    <TextAttribute
                                      key={attr.id}
                                      attributeSet={attrSet}
                                      attribute={attr}
                                      selected={
                                        item.selectedAttributes.find(
                                          (a) => a.attributeSetId === attrSet.id
                                        )?.selectedValue === attr.id
                                      }
                                      inCart={true}
                                    />
                                  ))
                                : attrSet.items.map((attr) => (
                                    <SwatchAttribute
                                      key={attr.id}
                                      attributeSet={attrSet}
                                      attribute={attr}
                                      selected={
                                        item.selectedAttributes.find(
                                          (a) => a.attributeSetId === attrSet.id
                                        )?.selectedValue === attr.id
                                      }
                                      inCart={true}
                                    />
                                  ))}
                            </div>
                          </div>
                        ))}
                      </div>

                      <div className="flex flex-col items-center justify-between w-fit">
                        <button
                          className="border cursor-pointer flex-center w-6 h-6"
                          onClick={() => updateItemQuantity(item.id, -1)}
                          data-testid="cart-item-amount-decrease"
                        >
                          <MinusIcon className="w-4 h-4" />
                        </button>
                        <span data-testid="cart-item-amount">
                          {item.quantity}
                        </span>
                        <button
                          className="border cursor-pointer flex-center w-6 h-6"
                          onClick={() => updateItemQuantity(item.id, 1)}
                          data-testid="cart-item-amount-increase"
                        >
                          <PlusIcon className="w-4 h-4" />
                        </button>
                      </div>
                    </div>
                    <div className="flex-center max-w-32">
                      <img
                        src={getThumbnail(item.product.gallery).imageUrl}
                        alt={item.product.name}
                        className="h-full w-full object-contain rounded object-center"
                      />
                    </div>
                  </div>
                );
              })}
            </div>
            <div
              className="flex justify-between items-center font-primary font-bold text-lg"
              data-testid="cart-total"
            >
              <span>Total</span>
              <span>{totalPrice}</span>
            </div>
          </div>
          <button
            className={`text-white font-primary text-md font-semibold py-4 px-8 transition-colors duration-300 min-w-2xs h-12 flex-center ${
              items.length === 0
                ? "bg-neutral-gray cursor-not-allowed"
                : "bg-primary cursor-pointer hover:bg-primary/80"
            }`}
            onClick={handlePlaceOrder}
            disabled={items.length === 0}
          >
            PLACE ORDER
          </button>
        </>
      )}
    </div>
  );
}
