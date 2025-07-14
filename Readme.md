# SW - E-commerce Project

A full-stack e-commerce application with GraphQL API backend and React frontend.

## Project Structure

```
SW/
├── backend/                 # PHP GraphQL API
│   ├── src/
│   │   ├── Controller/     # GraphQL controller
│   │   ├── Database/       # Database management & data loaders
│   │   ├── Entity/         # Database entities/models
│   │   └── GraphQL/        # GraphQL schema & resolvers
│   ├── data/               # Database population scripts
│   └── public/             # Web server entry point
└── frontend/               # React TypeScript application
    └── scandi-web-ecommerce/
        └── src/            # React components & logic
```

## Backend Structure

```
backend/
├── src/
│   ├── Controller/
│   │   └── GraphQLController.php
│   ├── Database/
│   │   ├── DatabaseManager.php
│   │   └── DataLoader/
│   │       ├── AttributeDataLoader.php
│   │       ├── BaseDataLoader.php
│   │       ├── CategoryDataLoader.php
│   │       ├── EcommerceDataLoaderManager.php
│   │       ├── OrderDataLoader.php
│   │       └── ProductDataLoader.php
│   ├── Entity/
│   │   ├── Attributes.php
│   │   ├── Categories.php
│   │   ├── Currency.php
│   │   ├── Orders.php
│   │   └── Products.php
│   └── GraphQL/
│       ├── Resolvers/
│       │   ├── AttributeSetResolvers.php
│       │   ├── BaseResolver.php
│       │   ├── CategoryResolvers.php
│       │   ├── MutationResolvers.php
│       │   ├── OrderItemResolvers.php
│       │   ├── OrderResolvers.php
│       │   ├── ProductResolvers.php
│       │   ├── QueryResolvers.php
│       │   └── ResolverManager.php
│       └── schema.graphql
├── data/
│   ├── ExampleDataManager.php      # Database population script
│   ├── drop/
│   │   └── dropTables.php          # Drop all tables from the database
│   └── init/
│       └── pdoOption.php           # Populate the database with initial data using PDO classes
│       └── cmdOption.php           # Populate the database with initial data using Command Line
│       └── oneLinerOption.php      # Populate the database with initial data using one-liner functions
│   ├── mysql.sql                   # MySQL database schema
│   └── sqlite.sql                  # SQLite database schema
└── public/
    └── index.php           # Entry point
```

## Database Setup

### Database Options

- **SQLite**: Used for quicker development and testing
- **MySQL**: The main production database

### Initial Data Population

The `backend/data/ExampleDataManager.php` file contains scripts to populate your database with initial data. This is useful for:

- Setting up sample products, categories, and attributes
- Creating test users and orders
- Initializing the database schema

It also provides a way to drop all tables from the database.

#### Population Options

I have created 3 options to populate the database.
It does not matter which one you use, they all do the same thing and make you follow the same steps.

**Option 1: Using PDO Classes**

```php
php data/init/pdoOption.php
```

**Option 2: Using Command Line**

```php
php data/init/cmdOption.php
```

**Option 3: Simple One-Liner Functions**

```php
php data/init/oneLinerOption.php
```

## Dropping Tables

If you want to drop all tables from the database in easy way, you can use the following script.

```php
php data/drop/dropTables.php
```

## Quick Start

1. **Backend Setup**

   ```bash
   cd backend
   composer install
   ```

2. **Frontend Setup**
   ```bash
   cd frontend/scandi-web-ecommerce
   npm install
   npm run dev
   ```

## Development Notes

- Use SQLite for local development to speed up setup
- MySQL is the primary database for production
- GraphQL API provides flexible data querying
- React frontend with TypeScript for type safety
