import type { Price, ProductImage } from "@/types/Product";

export const getThumbnail = (gallery: ProductImage[]): ProductImage => {
  return JSON.parse(JSON.stringify(gallery)).sort(
    (a: ProductImage, b: ProductImage) =>
      (a.sortOrder ?? 0) - (b.sortOrder ?? 0)
  )[0];
};

export const getPriceWithSymbol = (
  pricesWithQuantity:
    | { prices: Price[]; quantity?: number }
    | { prices: Price[]; quantity: number }[],
  currencyLabel: string = CURRENCY_LABEL
) => {
  // If array of products
  if (Array.isArray(pricesWithQuantity)) {
    const allHaveCurrency = pricesWithQuantity.every((item) =>
      item.prices.some((p) => p.currency.label === currencyLabel)
    );
    if (!allHaveCurrency) {
      return `Not all items have price in ${currencyLabel}`;
    }
    let total = 0;
    let symbol = "";
    pricesWithQuantity.forEach((item) => {
      const price = item.prices.find((p) => p.currency.label === currencyLabel);
      if (price) {
        total += price.amount * item.quantity;
        symbol = price.currency.symbol;
      }
    });
    return symbol + Number(total).toFixed(2);
  }

  // Single product
  if (Array.isArray(pricesWithQuantity.prices)) {
    const price = pricesWithQuantity.prices.find(
      (p) => p.currency.label === currencyLabel
    );
    if (!price) {
      return `Currency not found`;
    }
    return (
      price.currency.symbol +
      Number(price.amount * (pricesWithQuantity.quantity ?? 1)).toFixed(2)
    );
  }

  return "Invalid price data";
};

export const stripHtml = (html: string | undefined): string => {
  return html?.replace(/<[^>]+>/g, "") ?? "";
};

export const toKebabCase = (str: string) => {
  return str.toLowerCase().replace(/\s+/g, "-");
};

export const CURRENCY_LABEL = "USD";
