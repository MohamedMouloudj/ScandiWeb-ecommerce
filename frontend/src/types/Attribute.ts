export interface Attribute {
  id: string;
  displayValue: string;
  value: string;
}

export interface AttributeSet {
  id: string;
  name: string;
  type: AttributeType;
  items: Attribute[];
}

export type AttributeType = "TEXT" | "SWATCH";
