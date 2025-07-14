<?php

include_once __DIR__ . '/../ExampleDataManager.php';

try {
    $choice = prompt("Choose database (mysql/sqlite): ");

    if ($choice === 'mysql') {
        CommandLineExecutor::executeMySQLFile($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME'], __DIR__ . '/../mysql.sql');
    } elseif ($choice === 'sqlite') {
        CommandLineExecutor::executeSQLiteFile(__DIR__ . '/../../' . $_ENV['DB_NAME'] . '.sqlite', __DIR__ . '/../sqlite.sql');
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
