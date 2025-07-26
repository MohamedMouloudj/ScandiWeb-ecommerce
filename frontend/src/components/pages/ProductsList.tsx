import { useLoaderData, useLocation, useNavigation } from "react-router";
import ProductCard from "../ui/ProductCard";
import type { Product } from "@/types/Product";
import Spinner from "../ui/Spinner";
import Error from "../ui/Error";

export default function ProductsList() {
  const { products, error } = useLoaderData();
  const location = useLocation();
  const categoryName = location.state?.categoryName || "all";
  const navigation = useNavigation();
  const isLoading = navigation.state === "loading";

  if (error) {
    return <Error error={error} />;
  }

  if (products.length === 0) {
    return <p>No products found</p>;
  }
  return (
    <div className="flex flex-col items-center gap-16">
      <h1 className="text-start self-start">
        {categoryName?.charAt(0).toUpperCase() + categoryName?.slice(1)}
      </h1>
      {isLoading ? (
        <Spinner size={16} />
      ) : (
        <div className="grid-center">
          {products.map((product: Product) => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
      )}
    </div>
  );
}
