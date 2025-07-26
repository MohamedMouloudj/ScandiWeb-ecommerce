import type { Attribute } from "@/types/Attribute";

type TextAttributeProps = {
  attribute: Attribute;
  attributeSetId: string;
  selected: boolean;
  small?: boolean;
  onSelect?: (attributeSetId: string, attributeId: string) => void;
};

export default function TextAttribute({
  attribute,
  attributeSetId,
  selected,
  onSelect,
  small = false,
}: TextAttributeProps) {
  return (
    <div
      className={`${
        onSelect ? "cursor-pointer" : ""
      } border border-neutral-black flex-center ${
        small ? "min-w-6 min-h-6 px-1 py-1" : "min-w-20 min-h-12 px-5 py-4"
      } ${selected ? "bg-neutral-black" : "bg-background"}`}
      onClick={() => onSelect?.(attributeSetId, attribute.id)}
    >
      <span
        className={`${
          small ? "text-sm " : "text-md"
        } font-attribute-value font-normal ${
          selected ? "text-neutral-white" : "text-neutral-black"
        } flex-center select-none whitespace-nowrap`}
      >
        {attribute.value}
      </span>
    </div>
  );
}
