import type { Product } from "./Product";

export interface SelectedAttribute {
  attributeSetId: string;
  selectedValue: string;
}

export interface OrderItem {
  id: number;
  product: Product;
  quantity: number;
  selectedAttributes: SelectedAttribute[];
}

export interface Order {
  totalAmount: number;
  currency: string;
  items: OrderItem[];
}
