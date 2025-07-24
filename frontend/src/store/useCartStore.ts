import { create } from "zustand";
import createSelectors from "@/utils/createSelectors";
import { createJSONStorage, persist, devtools } from "zustand/middleware";

type CartItem = {
  id: string;
  name: string;
  quantity: number;
};

type CartState = {
  items: CartItem[];
  isOpen: boolean;
  addItem: (item: CartItem) => void;
  updateItem: (id: string, item: Partial<CartItem>) => void;
  removeItem: (id: string) => void;
  clearCart: () => void;
  toggleCart: () => void;
};

const useCart = createSelectors(
  create<CartState>()(
    devtools(
      persist(
        (set) => ({
          items: [],
          isOpen: false,
          addItem: (item) =>
            set((state) => ({ items: [...state.items, item] })),
          updateItem: (id, item) =>
            set((state) => ({
              items: state.items.map((cartItem) =>
                cartItem.id === id ? { ...cartItem, ...item } : cartItem
              ),
            })),
          removeItem: (id) =>
            set((state) => ({
              items: state.items.filter((item) => item.id !== id),
            })),
          clearCart: () => set({ items: [] }),
          toggleCart: () => set((state) => ({ isOpen: !state.isOpen })),
        }),
        {
          name: "cart",
          storage: createJSONStorage(() => localStorage),
          partialize: (state) => ({ items: state.items }),
        }
      ),
      {
        name: "cart-store",
        enabled: process.env.NODE_ENV === "development",
      }
    )
  )
);

export default useCart;
