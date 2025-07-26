<?php

class CreateInitialSchema
{
    public function up(\PDO $pdo): void
    {
        // Create currencies table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS currencies (
                id INT AUTO_INCREMENT PRIMARY KEY,
                label VARCHAR(255) NOT NULL,
                symbol VARCHAR(10) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create categories table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) UNIQUE NOT NULL,
                type VARCHAR(50) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create products table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS products (
                id VARCHAR(255) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                in_stock BOOLEAN NOT NULL DEFAULT TRUE,
                description TEXT,
                category_id INT,
                brand VARCHAR(255),
                product_type VARCHAR(50) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_category (category_id),
                INDEX idx_product_type (product_type),
                INDEX idx_in_stock (in_stock),
                FOREIGN KEY (category_id) REFERENCES categories(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create product_prices table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS product_prices (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id VARCHAR(255) NOT NULL,
                amount DECIMAL(10, 2) NOT NULL,
                currency_id INT NOT NULL,
                INDEX idx_product_price (product_id),
                INDEX idx_currency (currency_id),
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                FOREIGN KEY (currency_id) REFERENCES currencies(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create product_gallery table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS product_gallery (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id VARCHAR(255) NOT NULL,
                image_url TEXT NOT NULL,
                sort_order INT NOT NULL DEFAULT 0,
                INDEX idx_product_gallery (product_id, sort_order),
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create attribute_sets table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS attribute_sets (
                id VARCHAR(255) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                type VARCHAR(50) NOT NULL,
                attribute_type VARCHAR(50) NOT NULL,
                INDEX idx_attribute_type (attribute_type)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create attributes table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS attributes (
                id VARCHAR(255) PRIMARY KEY,
                attribute_set_id VARCHAR(255) NOT NULL,
                display_value VARCHAR(255) NOT NULL,
                value VARCHAR(255) NOT NULL,
                attr_type VARCHAR(50) NOT NULL,
                INDEX idx_attribute_set (attribute_set_id),
                INDEX idx_attr_type (attr_type),
                FOREIGN KEY (attribute_set_id) REFERENCES attribute_sets(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create product_attributes table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS product_attributes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id VARCHAR(255) NOT NULL,
                attribute_set_id VARCHAR(255) NOT NULL,
                INDEX idx_product_attributes (product_id, attribute_set_id),
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                FOREIGN KEY (attribute_set_id) REFERENCES attribute_sets(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create orders table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                total_amount DECIMAL(10, 2) NOT NULL,
                currency_id INT NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_created_at (created_at),
                FOREIGN KEY (currency_id) REFERENCES currencies(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create order_items table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id VARCHAR(255) NOT NULL,
                quantity INT NOT NULL DEFAULT 1,
                selected_attributes JSON,
                INDEX idx_order_items (order_id),
                INDEX idx_product_order (product_id),
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        echo "Tables created successfully!\n";
    }

    public function down(\PDO $pdo): void
    {
        $tables = [
            'order_items',
            'orders',
            'product_attributes',
            'attributes',
            'attribute_sets',
            'product_gallery',
            'product_prices',
            'products',
            'categories',
            'currencies'
        ];

        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS {$table}");
        }
    }
}
