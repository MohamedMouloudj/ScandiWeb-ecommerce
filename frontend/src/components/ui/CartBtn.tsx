import useCart from "@/store/useCartStore";

export default function CartBtn() {
  const { items, toggleCart } = useCart();
  const showBubble = items.length > 0;

  const handleClick = () => {
    toggleCart();
  };

  return (
    <div className="flex-1/3 flex justify-end">
      <button
        className="relative cursor-pointer p-2"
        onClick={handleClick}
        data-testid="cart-btn"
      >
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
