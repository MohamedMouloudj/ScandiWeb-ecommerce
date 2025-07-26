<?php

require_once __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/migrations/001_create_initial_schema.php';
require_once __DIR__ . '/seeds/InitialDataSeeder.php';

try {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    echo "Connected to database successfully!\n";

    // Run migration
    $migration = new CreateInitialSchema();
    $migration->up($pdo);

    // Run seeder
    $seeder = new InitialDataSeeder();
    $seeder->run($pdo);

    echo "Database setup completed!\n";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
