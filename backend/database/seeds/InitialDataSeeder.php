<?php
class InitialDataSeeder
{
    public function run(\PDO $pdo): void
    {
        // Insert currencies
        $pdo->exec("
            INSERT IGNORE INTO currencies (label, symbol, created_at) VALUES 
            ('USD', '$', NOW())
        ");

        // Insert categories
        $pdo->exec("
            INSERT IGNORE INTO categories (name, type, created_at) VALUES 
            ('all', 'general', NOW()),
            ('clothes', 'clothing', NOW()),
            ('tech', 'tech', NOW())
        ");

        // Insert attribute sets
        $pdo->exec("
            INSERT IGNORE INTO attribute_sets (id, name, type, attribute_type) VALUES 
            ('Size', 'Size', 'text', 'text'),
            ('Color', 'Color', 'swatch', 'swatch'),
            ('Capacity', 'Capacity', 'text', 'text'),
            ('With USB 3 ports', 'With USB 3 ports', 'text', 'text'),
            ('Touch ID in keyboard', 'Touch ID in keyboard', 'text', 'text')
        ");

        // Insert attributes
        $attributes = [
            // Size attributes
            ['40', 'Size', '40', '40', 'size'],
            ['41', 'Size', '41', '41', 'size'],
            ['42', 'Size', '42', '42', 'size'],
            ['43', 'Size', '43', '43', 'size'],
            ['Small', 'Size', 'Small', 'S', 'size'],
            ['Medium', 'Size', 'Medium', 'M', 'size'],
            ['Large', 'Size', 'Large', 'L', 'size'],
            ['Extra Large', 'Size', 'Extra Large', 'XL', 'size'],

            // Color attributes
            ['Green', 'Color', 'Green', '#44FF03', 'color'],
            ['Cyan', 'Color', 'Cyan', '#03FFF7', 'color'],
            ['Blue', 'Color', 'Blue', '#030BFF', 'color'],
            ['Black', 'Color', 'Black', '#000000', 'color'],
            ['White', 'Color', 'White', '#FFFFFF', 'color'],

            // Capacity attributes
            ['512G', 'Capacity', '512G', '512G', 'text'],
            ['1T', 'Capacity', '1T', '1T', 'text'],
            ['256GB', 'Capacity', '256GB', '256GB', 'text'],
            ['512GB', 'Capacity', '512GB', '512GB', 'text'],

            // USB ports
            ['Yes', 'With USB 3 ports', 'Yes', 'Yes', 'text'],
            ['No', 'With USB 3 ports', 'No', 'No', 'text'],

            // Touch ID
            ['Yes-Touch', 'Touch ID in keyboard', 'Yes', 'Yes', 'text'],
            ['No-Touch', 'Touch ID in keyboard', 'No', 'No', 'text']
        ];

        $stmt = $pdo->prepare("
            INSERT IGNORE INTO attributes (id, attribute_set_id, display_value, value, attr_type) 
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($attributes as $attr) {
            $stmt->execute($attr);
        }

        // Insert products
        $pdo->exec("
            INSERT IGNORE INTO products (id, name, in_stock, description, category_id, brand, product_type, created_at) VALUES 
            ('huarache-x-stussy-le', 'Nike Air Huarache Le', TRUE, '<p>Great sneakers for everyday use!</p>', 2, 'Nike x Stussy', 'clothing', NOW()),
            ('jacket-canada-goosee', 'Jacket', TRUE, '<p>Awesome winter jacket</p>', 2, 'Canada Goose', 'clothing', NOW()),
            ('ps-5', 'PlayStation 5', TRUE, '<p>A good gaming console. Plays games of PS4! Enjoy if you can buy it mwahahahaha</p>', 3, 'Sony', 'tech', NOW()),
            ('xbox-series-s', 'Xbox Series S 512GB', FALSE, '<div><ul><li><span>Hardware-beschleunigtes Raytracing macht dein Spiel noch realistischer</span></li><li><span>Spiele Games mit bis zu 120 Bilder pro Sekunde</span></li><li><span>Minimiere Ladezeiten mit einer speziell entwickelten 512GB NVMe SSD und wechsle mit Quick Resume nahtlos zwischen mehreren Spielen.</span></li></ul></div>', 3, 'Microsoft', 'tech', NOW()),
            ('apple-imac-2021', 'iMac 2021', TRUE, 'The new iMac!', 3, 'Apple', 'tech', NOW()),
            ('apple-iphone-12-pro', 'iPhone 12 Pro', TRUE, 'This is iPhone 12. Nothing else to say.', 3, 'Apple', 'tech', NOW()),
            ('apple-airpods-pro', 'AirPods Pro', FALSE, '<h3>Magic like you''ve never heard</h3><p>AirPods Pro have been designed to deliver Active Noise Cancellation for immersive sound, Transparency mode so you can hear your surroundings, and a customizable fit for all-day comfort.</p>', 3, 'Apple', 'tech', NOW()),
            ('apple-airtag', 'AirTag', TRUE, '<h1>Lose your knack for losing things.</h1><p>AirTag is an easy way to keep track of your stuff. Attach one to your keys, slip another one in your backpack.</p>', 3, 'Apple', 'tech', NOW())
        ");

        // Insert product prices
        $pdo->exec("
            INSERT IGNORE INTO product_prices (product_id, amount, currency_id) VALUES
            ('huarache-x-stussy-le', 144.69, 1),
            ('jacket-canada-goosee', 518.47, 1),
            ('ps-5', 844.02, 1),
            ('xbox-series-s', 333.99, 1),
            ('apple-imac-2021', 1688.03, 1),
            ('apple-iphone-12-pro', 1000.76, 1),
            ('apple-airpods-pro', 300.23, 1),
            ('apple-airtag', 120.57, 1)
        ");

        // Insert product gallery
        $pdo->exec("
            INSERT IGNORE INTO product_gallery (product_id, image_url, sort_order) VALUES 
            ('huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_2_720x.jpg?v=1612816087', 0),
            ('huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_1_720x.jpg?v=1612816087', 1),
            ('huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_3_720x.jpg?v=1612816087', 2),
            ('jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016105/product-image/2409L_61.jpg', 0),
            ('jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016107/product-image/2409L_61_a.jpg', 1),
            ('ps-5', 'https://images-na.ssl-images-amazon.com/images/I/510VSJ9mWDL._SL1262_.jpg', 0),
            ('ps-5', 'https://images-na.ssl-images-amazon.com/images/I/610%2B69ZsKCL._SL1500_.jpg', 1),
            ('xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/71vPCX0bS-L._SL1500_.jpg', 0),
            ('apple-imac-2021', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/imac-24-blue-selection-hero-202104?wid=904&hei=840&fmt=jpeg&qlt=80&.v=1617492405000', 0),
            ('apple-iphone-12-pro', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-12-pro-family-hero?wid=940&hei=1112&fmt=jpeg&qlt=80&.v=1604021663000', 0),
            ('apple-airpods-pro', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MWP22?wid=572&hei=572&fmt=jpeg&qlt=95&.v=1591634795000', 0),
            ('apple-airtag', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/airtag-double-select-202104?wid=445&hei=370&fmt=jpeg&qlt=95&.v=1617761672000', 0)
        ");

        // Insert product attributes
        $pdo->exec("
            INSERT IGNORE INTO product_attributes (product_id, attribute_set_id) VALUES 
            ('huarache-x-stussy-le', 'Size'),
            ('jacket-canada-goosee', 'Size'),
            ('ps-5', 'Color'),
            ('ps-5', 'Capacity'),
            ('xbox-series-s', 'Color'),
            ('xbox-series-s', 'Capacity'),
            ('apple-imac-2021', 'Capacity'),
            ('apple-imac-2021', 'With USB 3 ports'),
            ('apple-imac-2021', 'Touch ID in keyboard'),
            ('apple-iphone-12-pro', 'Capacity'),
            ('apple-iphone-12-pro', 'Color')
        ");

        echo "Initial data seeded successfully!\n";
    }
}
