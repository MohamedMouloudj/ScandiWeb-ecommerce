import { NavLink, useLoaderData, useLocation } from "react-router";
import Logo from "@/components/ui/Logo";
import Cart from "@/components/ui/CartBtn";

type Category = {
  id: number;
  name: string;
};

export default function Navbar() {
  const location = useLocation();
  const { categories, error, loading } = useLoaderData();

  const isActive = (path: string) => {
    return location.pathname.includes(path);
  };

  return (
    <header>
      <nav>
        {loading ? (
          <div>Loading categories...</div>
        ) : error ? (
          <div>Error loading categories</div>
        ) : (
          <ul>
            {categories.map((category: Category) => (
              <li
                key={category.id}
                className={`${
                  isActive(`/category/${category.id}`)
                    ? "border-b-2 border-primary"
                    : ""
                }`}
              >
                <NavLink
                  to={`/products/${category.id}`}
                  className={
                    isActive(`/products/${category.id}`) ? "text-primary" : ""
                  }
                  data-testid={
                    isActive(`/products/${category.id}`)
                      ? "active-category-link"
                      : "category-link"
                  }
                >
                  {category.name}
                </NavLink>
              </li>
            ))}
          </ul>
        )}
        <Logo />
        <Cart />
      </nav>
    </header>
  );
}
