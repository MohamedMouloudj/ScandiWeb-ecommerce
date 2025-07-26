import { Outlet } from "react-router";
import Navbar from "@/components/ui/layout/Navbar";
import useCart from "@/store/useCartStore";

export default function AppLayout() {
  const { isOpen, toggleCart } = useCart();
  return (
    <>
      <Navbar />
      {isOpen && (
        <div
          className="fixed inset-0 top-16 bg-cart-modal-background z-30 pointer-events-auto"
          onClick={toggleCart}
        />
      )}
      <main className="app-container">
        <Outlet />
      </main>
    </>
  );
}
