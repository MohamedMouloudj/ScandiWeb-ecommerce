import {
  NavLink,
  useLoaderData,
  useLocation,
  useNavigation,
  useParams,
} from "react-router";
import Logo from "@/components/ui/Logo";
import Cart from "@/components/ui/CartBtn";
import Spinner from "@/components/ui/Spinner";
import CartModal from "@/components/ui/CartModal";

type Category = {
  id: number;
  name: string;
};

export default function Navbar() {
  const { categories, error } = useLoaderData();
  const location = useLocation();
  const { categoryName } = useParams();

  const navigation = useNavigation();
  const isLoading = navigation.state === "loading";

  const isActive = (category: Category) => {
    if (category.name === "all") {
      return (
        location.pathname === "/" ||
        location.pathname === "/all" ||
        !categoryName
      );
    }

    return categoryName === category.name;
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
              return (
                <li
                  key={category.id}
                  className={`${
                    isActive(category) ? "border-b-2 border-primary" : ""
                  }`}
                >
                  <NavLink
                    to={`/${category.name}`}
                    state={{ categoryName: category.name }}
                    className={`${
                      isActive(category) ? "text-primary" : ""
                    } h-full`}
                    data-testid={
                      isActive(category)
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
