import { Outlet } from "react-router";
import Navbar from "@/components/ui/layout/Navbar";
import useCart from "@/store/useCartStore";

export default function AppLayout() {
  const { isOpen, toggleCart } = useCart();
  return (
    <>
      <Navbar />
      <div
        className={`fixed inset-0 top-16 bg-cart-modal-background z-30 pointer-events-auto transition-all duration-300 ${
          isOpen
            ? "opacity-100 visible"
            : "opacity-0 invisible pointer-events-none"
        }`}
        onClick={toggleCart}
      />
      <main className="app-container">
        <Outlet />
      </main>
    </>
  );
}
