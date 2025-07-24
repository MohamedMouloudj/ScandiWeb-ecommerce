import { gql } from "@apollo/client";

// categories
export const GET_CATEGORIES = gql`
  query GetCategories {
    categories {
      id
      name
    }
  }
`;

// products by category
export const GET_PRODUCTS_BY_CATEGORY = gql`
  query GetProductsByCategory($categoryId: Int) {
    products(categoryId: $categoryId) {
      id
      name
      inStock
      brand
      prices {
        amount
        currency {
          label
          symbol
        }
      }
      gallery {
        id
        imageUrl
        sortOrder
      }
      attributes {
        id
        name
        type
        items {
          id
          displayValue
          value
        }
      }
    }
  }
`;

// single product
export const GET_PRODUCT = gql`
  query GetProduct($id: String!) {
    product(id: $id) {
      id
      name
      inStock
      description
      brand
      prices {
        amount
        currency {
          label
          symbol
        }
      }
      gallery {
        id
        imageUrl
        sortOrder
      }
      attributes {
        id
        name
        type
        items {
          id
          displayValue
          value
        }
      }
      category {
        id
        name
      }
    }
  }
`;

// place order
export const PLACE_ORDER = gql`
  mutation PlaceOrder($input: PlaceOrderInput!) {
    placeOrder(input: $input) {
      id
      totalAmount
      currency
      items {
        id
        product {
          id
          name
        }
        quantity
        selectedAttributes {
          attributeSetId
          selectedValue
        }
      }
    }
  }
`;
