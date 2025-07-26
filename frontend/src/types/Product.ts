import type { Category } from "@/types/Category";
import type { AttributeSet } from "./Attribute";

export interface Price {
  amount: number;
  currency: {
    label: string;
    symbol: string;
  };
}

export interface ProductImage {
  id?: string;
  imageUrl: string;
  sortOrder?: number;
}

export interface Product {
  id: string;
  name: string;
  description?: string;
  inStock: boolean;
  brand: string;
  prices: Price[];
  gallery: ProductImage[];
  category: Category;
  attributes?: AttributeSet[];
}
