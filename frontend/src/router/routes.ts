import { createBrowserRouter } from "react-router";
import AppLayout from "@/components/ui/layout/AppLayout";
import ProductsList from "@/components/pages/ProductsList";
import ProductDetails from "@/components/pages/ProductDetails";

import {
  categoriesLoader,
  placeOrder,
  productLoader,
  productsByCategoryLoader,
} from "@/services/data";

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
        path: "/:categoryName",
        Component: ProductsList,
        loader: ({ params }) => productsByCategoryLoader({ params }),
      },
      {
        path: "/product/:productId",
        Component: ProductDetails,
        loader: ({ params }) => productLoader({ params }),
      },
    ],
  },
]);
