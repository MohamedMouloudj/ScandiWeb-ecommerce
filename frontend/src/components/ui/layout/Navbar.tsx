import {
  NavLink,
  useLoaderData,
  useLocation,
  useNavigation,
} from "react-router";
import Logo from "@/components/ui/Logo";
import Cart from "@/components/ui/CartBtn";
import Spinner from "../Spinner";
import CartModal from "../CartModal";

type Category = {
  id: number;
  name: string;
};

export default function Navbar() {
  const location = useLocation();
  const { categories, error } = useLoaderData();
  const navigation = useNavigation();
  const isLoading = navigation.state === "loading";

  const isActive = (path: string) => {
    return location.pathname === path;
  };

  return (
    <header>
      <nav>
        {isLoading ? (
          <Spinner />
        ) : error ? (
          <div>Error loading categories: {error}</div>
        ) : (
          <ul>
            {categories.map((category: Category) => {
              const activeRoute =
                category.name === "all" ? "/" : `/category/${category.id}`;
              return (
                <li
                  key={category.id}
                  className={`${
                    isActive(activeRoute) ? "border-b-2 border-primary" : ""
                  }`}
                >
                  <NavLink
                    to={activeRoute}
                    state={{ categoryName: category.name }}
                    className={`${
                      isActive(activeRoute) ? "text-primary" : ""
                    } h-full`}
                    data-testid={
                      isActive(activeRoute)
                        ? "active-category-link"
                        : "category-link"
                    }
                  >
                    {category.name.toUpperCase()}
                  </NavLink>
                </li>
              );
            })}
          </ul>
        )}
        <Logo />
        <Cart />
      </nav>
      <CartModal />
    </header>
  );
}
