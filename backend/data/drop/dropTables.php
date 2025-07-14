<?php

include_once __DIR__ . '/../ExampleDataManager.php';

$config = [
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'sqliteDatabase' => __DIR__ . '/../../' . $_ENV['DB_NAME'] . '.sqlite'
];

try {
    $choice = prompt("Choose database (mysql/sqlite/all): ");

    if ($choice === 'mysql') {
        DropAllTables::dropAllTables($config, 'mysql');
    } elseif ($choice === 'sqlite') {
        DropAllTables::dropAllTables($config, 'sqlite');
    } elseif ($choice === 'all') {
        DropAllTables::dropAllTables($config, 'all');
    } else {
        echo "Invalid option. Please type 'mysql', 'sqlite', or 'all'.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
