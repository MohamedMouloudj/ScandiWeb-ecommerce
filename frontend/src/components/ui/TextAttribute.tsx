import type { Attribute, AttributeSet } from "@/types/Attribute";
import { toKebabCase } from "@/utils/helpers";

type TextAttributeProps = {
  attribute: Attribute;
  attributeSet: AttributeSet;
  selected: boolean;
  inCart?: boolean;
  onSelect?: (attributeSetId: string, attributeId: string) => void;
};

export default function TextAttribute({
  attribute,
  attributeSet,
  selected,
  onSelect,
  inCart = false,
}: TextAttributeProps) {
  if (inCart) {
    return (
      <div
        className={`${
          onSelect ? "cursor-pointer" : ""
        } border border-neutral-black flex-center ${"min-w-6 min-h-6 px-1 py-1"} ${
          selected ? "bg-neutral-black" : "bg-background"
        }`}
        onClick={() => onSelect?.(attributeSet.id, attribute.id)}
        data-testid={`cart-item-attribute-${toKebabCase(
          attributeSet.name
        )}-${toKebabCase(attribute.displayValue)}${
          selected ? "-selected" : ""
        }`}
      >
        <span
          className={`${"text-sm "} font-attribute-value font-normal ${
            selected ? "text-neutral-white" : "text-neutral-black"
          } flex-center select-none whitespace-nowrap`}
        >
          {attribute.value}
        </span>
      </div>
    );
  }
  return (
    <div
      className={`${
        onSelect ? "cursor-pointer" : ""
      } border border-neutral-black flex-center ${"min-w-20 min-h-12 px-5 py-4"} ${
        selected ? "bg-neutral-black" : "bg-background"
      }`}
      onClick={() => onSelect?.(attributeSet.id, attribute.id)}
    >
      <span
        className={`${"text-md"} font-attribute-value font-normal ${
          selected ? "text-neutral-white" : "text-neutral-black"
        } flex-center select-none whitespace-nowrap`}
      >
        {attribute.value}
      </span>
    </div>
  );
}
