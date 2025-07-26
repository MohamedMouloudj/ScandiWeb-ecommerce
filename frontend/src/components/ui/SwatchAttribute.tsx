import type { Attribute, AttributeSet } from "@/types/Attribute";
import { toKebabCase } from "@/utils/helpers";

type SwatchAttributeProps = {
  attribute: Attribute;
  attributeSet: AttributeSet;
  selected: boolean;
  inCart?: boolean;
  onSelect?: (attributeSetId: string, attributeId: string) => void;
};

export default function SwatchAttribute({
  attributeSet,
  selected,
  attribute,
  onSelect,
  inCart = false,
}: SwatchAttributeProps) {
  if (inCart) {
    return (
      <div
        className={`${onSelect ? "cursor-pointer" : ""} w-4 h-4 ${
          selected ? `${"ring-1 ring-offset-1"} ring-primary` : ""
        } ${
          attribute.value === "#FFFFFF" ? "border-1 border-neutral-black" : ""
        }`}
        style={{ backgroundColor: attribute.value }}
        onClick={() => onSelect?.(attributeSet.id, attribute.id)}
        data-testid={`cart-item-attribute-${toKebabCase(
          attributeSet.name
        )}-${toKebabCase(attribute.displayValue)}${
          selected ? "-selected" : ""
        }`}
      />
    );
  }
  return (
    <div
      className={`${onSelect ? "cursor-pointer" : ""} ${"w-9 h-9 "} ${
        selected ? `${"ring-2 ring-offset-2"} ring-primary` : ""
      } ${
        attribute.value === "#FFFFFF" ? "border-1 border-neutral-black" : ""
      }`}
      style={{ backgroundColor: attribute.value }}
      onClick={() => onSelect?.(attributeSet.id, attribute.id)}
    />
  );
}
