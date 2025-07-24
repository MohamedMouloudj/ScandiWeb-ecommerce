import { createBrowserRouter } from "react-router";
import AppLayout from "@/components/ui/layout/AppLayout";
import ProductsList from "@/components/pages/ProductsList";

import {
  categoriesLoader,
  placeOrder,
  productLoader,
  productsByCategoryLoader,
} from "@/services/data";
import ProductDetails from "@/components/pages/ProductDetails";

export const router = createBrowserRouter([
  {
    path: "/",
    Component: AppLayout,
    loader: categoriesLoader,
    action: ({ request }) => placeOrder({ request }),
    children: [
      {
        index: true,
        Component: ProductsList,
        loader: ({ params }) => productsByCategoryLoader({ params }),
      },
      {
        path: "/products/:categoryId",
        Component: ProductsList,
        loader: ({ params }) => productsByCategoryLoader({ params }),
      },
      {
        path: "/products/:productId",
        Component: ProductDetails,
        loader: ({ params }) => productLoader({ params }),
      },
    ],
  },
]);
