# Backend

This is the backend for the SW project. It is built with PHP and provides APIs for managing products, categories, orders, and attributes.

## Getting Started

### Requirements

- PHP 7.4+
- Composer

### Running the Development Server

```sh
php -S localhost:8000 -t public
```

## Database Schema

Below is an overview of the main database entities and their relationships, represented in Mermaid ER diagram syntax.

```mermaid
erDiagram
    PRODUCT {
        int id PK
        string name
        float price
        int category_id FK
    }
    CATEGORY {
        int id PK
        string name
        int parent_id FK
    }
    ATTRIBUTE {
        int id PK
        string name
    }
    ATTRIBUTE_SET {
        int id PK
        string name
    }
    PRODUCT_ATTRIBUTE {
        int id PK
        int product_id FK
        int attribute_id FK
        string value
    }
    PRODUCT_IMAGE {
        int id PK
        int product_id FK
        string url
    }
    ORDER {
        int id PK
        float total
        string status
        int currency_id FK
    }
    CURRENCY {
        int id PK
        string code
        string symbol
    }

    PRODUCT ||--o{ PRODUCT_ATTRIBUTE : "has"
    PRODUCT ||--o{ PRODUCT_IMAGE : "has"
    PRODUCT }o--|| CATEGORY : "belongs to"
    CATEGORY ||--o{ CATEGORY : "parent of"
    ATTRIBUTE_SET ||--o{ ATTRIBUTE : "contains"
    PRODUCT_ATTRIBUTE }o--|| ATTRIBUTE : "is"
    ORDER }o--|| CURRENCY : "uses"
```

## Entities

- **Product**: Represents a product for sale.
- **Category**: Organizes products into categories (supports parent-child relationships).
- **Attribute**: Defines a characteristic (e.g., color, size).
- **AttributeSet**: Groups attributes together.
- **ProductAttribute**: Assigns attribute values to products.
- **ProductImage**: Stores images for products.
- **Order**: Represents a customer order.
- **Currency**: Represents the currency used in orders.
