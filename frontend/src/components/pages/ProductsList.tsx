import React from "react";
import { useLoaderData } from "react-router";

export default function ProductsList() {
  const { products } = useLoaderData();
  const category = products[0].category.name;
  return (
    <div>
      <h1>{category}</h1>
      <div></div>
    </div>
  );
}
