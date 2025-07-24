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
    if (error) {
      throw new Error("Failed to fetch categories: " + error.message);
    }
    return { categories: data.categories, error, loading: false };
  } catch (error) {
    console.error("Error fetching categories:", error);
    return {
      categories: [],
      error: "Failed to fetch categories",
      loading: false,
    };
  }
};

export const productsByCategoryLoader = async ({
  params,
}: {
  params: { categoryId?: string };
}) => {
  const { categoryId } = params;
  try {
    const { data, error } = await apolloClient.query({
      query: GET_PRODUCTS_BY_CATEGORY,
      variables: { categoryId: categoryId ? parseInt(categoryId) : null },
    });
    if (error) {
      throw new Error("Failed to fetch products by category: " + error.message);
    }
    return data.products;
  } catch (error) {
    console.error("Error fetching products by category:", error);
    throw new Error("Failed to fetch products by category");
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
      throw new Error("Product ID is required");
    }
    const { data, error } = await apolloClient.query({
      query: GET_PRODUCT,
      variables: { id: parseInt(productId) },
    });
    if (error) {
      throw new Error("Failed to fetch product: " + error.message);
    }
    return data.product;
  } catch (error) {
    console.error("Error fetching product:", error);
    throw error;
  }
};

export const placeOrder = async ({ request }: { request: Request }) => {
  try {
    const formData = await request.formData();
    const { data } = await apolloClient.mutate({
      mutation: PLACE_ORDER,
      variables: formData,
    });
    return data.placeOrder;
  } catch (error) {
    console.error("Error placing order:", error);
    throw new Error("Failed to place order");
  }
};
