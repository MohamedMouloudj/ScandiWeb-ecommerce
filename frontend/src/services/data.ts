import {
  GET_CATEGORIES,
  GET_PRODUCTS_BY_CATEGORY,
  GET_PRODUCT,
  PLACE_ORDER,
} from "@/services/gqlSchema";
import { apolloClient } from "@/services/apolloClient";

export const categoriesLoader = async () => {
  try {
    const { data, error } = await apolloClient.query({
      query: GET_CATEGORIES,
    });

    return { categories: data.categories, error };
  } catch (error) {
    console.error("Error fetching categories:", error);
    return { error: "Failed to fetch categories" };
  }
};

export const productsByCategoryLoader = async ({
  params,
}: {
  params: { categoryName?: string };
}) => {
  const { categoryName } = params;
  try {
    const { data, error } = await apolloClient.query({
      query: GET_PRODUCTS_BY_CATEGORY,
      variables: { categoryName: categoryName || null },
    });
    if (error) {
      throw new Error("Failed to fetch products by category: " + error.message);
    }
    return { products: data.products, error };
  } catch (error) {
    console.error("Error fetching products by category:", error);
    return { error: "Failed to fetch products by category" };
  }
};

export const productLoader = async ({
  params,
}: {
  params: { productId?: string };
}) => {
  try {
    const { productId } = params;
    if (!productId) {
      return { error: "Product ID is required" };
    }
    const { data, error } = await apolloClient.query({
      query: GET_PRODUCT,
      variables: { id: productId },
    });
    if (error) {
      return { error: "Failed to fetch product: " + error.message };
    }
    return { product: data.product, error };
  } catch (error) {
    console.error("Error fetching product:", error);
    return { error: "Failed to fetch product" };
  }
};

export const placeOrder = async ({ request }: { request: Request }) => {
  try {
    const formData = await request.formData();
    const orderData = JSON.parse(formData.get("orderData") as string);
    const { data, errors } = await apolloClient.mutate({
      mutation: PLACE_ORDER,
      variables: {
        input: orderData,
      },
    });
    return { data: data.placeOrder, error: errors };
  } catch (error) {
    console.error("Error placing order:", error);
    return { error: "Failed to place order" };
  }
};
