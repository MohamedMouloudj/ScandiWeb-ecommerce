import type { Attribute } from "@/types/Attribute";

type SwatchAttributeProps = {
  attribute: Attribute;
  attributeSetId: string;
  selected: boolean;
  small?: boolean;
  onSelect?: (attributeSetId: string, attributeId: string) => void;
};

export default function SwatchAttribute({
  attributeSetId,
  selected,
  attribute,
  onSelect,
  small = false,
}: SwatchAttributeProps) {
  return (
    <div
      className={`${onSelect ? "cursor-pointer" : ""} ${
        small ? "w-4 h-4" : "w-9 h-9 "
      } ${
        selected
          ? `${
              small ? "ring-1 ring-offset-1" : "ring-2 ring-offset-2"
            } ring-primary`
          : ""
      } ${
        attribute.value === "#FFFFFF" ? "border-1 border-neutral-black" : ""
      }`}
      style={{ backgroundColor: attribute.value }}
      onClick={() => onSelect?.(attributeSetId, attribute.id)}
    />
  );
}
