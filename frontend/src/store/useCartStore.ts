import { create } from "zustand";
import createSelectors from "@/utils/createSelectors";
import { createJSONStorage, persist, devtools } from "zustand/middleware";
import { immer } from "zustand/middleware/immer";
import type { OrderItem } from "@/types/Order";

type CartState = {
  items: OrderItem[];
  isOpen: boolean;
  addItem: (item: Partial<OrderItem>) => void;
  updateItemQuantity: (id: number, quantity: number) => void;
  removeItem: (id: number) => void;
  clearCart: () => void;
  toggleCart: () => void;
};

const useCart = createSelectors(
  create<CartState>()(
    devtools(
      immer(
        persist(
          (set) => ({
            items: [],
            isOpen: false,
            addItem: (item: Partial<OrderItem>) =>
              set((state: CartState) => {
                if (item.selectedAttributes?.length === 0) {
                  item.selectedAttributes =
                    item.product?.attributes?.map((attrSet) => ({
                      attributeSetId: attrSet.id,
                      selectedValue: attrSet.items[0].id,
                    })) ?? [];
                }
                const existingProduct = state.items.find(
                  (cartItem: OrderItem) =>
                    cartItem.product.id === item.product!.id &&
                    cartItem.selectedAttributes.length ===
                      (item.selectedAttributes?.length ?? 0) &&
                    cartItem.selectedAttributes.every((attr) =>
                      item.selectedAttributes?.some(
                        (a) =>
                          a.attributeSetId === attr.attributeSetId &&
                          a.selectedValue === attr.selectedValue
                      )
                    ) &&
                    (item.selectedAttributes ?? []).every((attr) =>
                      cartItem.selectedAttributes.some(
                        (a) =>
                          a.attributeSetId === attr.attributeSetId &&
                          a.selectedValue === attr.selectedValue
                      )
                    )
                );
                if (existingProduct) {
                  existingProduct.quantity += 1;
                } else {
                  state.items.push({
                    ...item,
                    id: state.items.length + 1,
                  } as unknown as OrderItem);
                }
              }),
            updateItemQuantity: (id: number, quantity: number) =>
              set((state: CartState) => {
                const cartItem = state.items.find((ci) => ci.id === id);
                if (cartItem) {
                  if (cartItem.quantity + quantity > 0) {
                    cartItem.quantity += quantity;
                  } else {
                    const index = state.items.findIndex((ci) => ci.id === id);
                    if (index !== -1) {
                      state.items.splice(index, 1);
                    }
                  }
                }
              }),
            removeItem: (id: number) =>
              set((state: CartState) => {
                const index = state.items.findIndex((ci) => ci.id === id);
                if (index !== -1) {
                  state.items.splice(index, 1);
                }
              }),
            clearCart: () => set({ items: [] }),
            toggleCart: () => set((state) => ({ isOpen: !state.isOpen })),
          }),
          {
            name: "cart",
            storage: createJSONStorage(() => localStorage),
            partialize: (state) => ({ items: state.items }),
          }
        )
      )
    )
  )
);

export default useCart;
