import useCart from "@/store/useCartStore";

export default function CartBtn() {
  const { items, toggleCart } = useCart();
  const showBubble = items.length > 0;

  return (
    <div className="flex-1/3 flex justify-end" data-testid="cart-btn">
      <button className="relative cursor-pointer p-2" onClick={toggleCart}>
        <img src="/images/EmptyCartDark.svg" alt="cart" className="w-6 h-6" />
        {showBubble && (
          <span className="absolute -top-1 -right-1 bg-neutral-black text-white text-xs rounded-full px-2 py-0.5 min-w-[24px] text-center">
            {items.length}
          </span>
        )}
      </button>
    </div>
  );
}
