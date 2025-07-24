import React from "react";
import { useParams } from "react-router";

export default function ProductDetails() {
  const { categoryId } = useParams();
  return (
    <div>
      <h1>{categoryId}</h1>
    </div>
  );
}
