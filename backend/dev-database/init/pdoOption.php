<?php

require_once __DIR__ . '/../../bootstrap.php';
include_once __DIR__ . '/../ExampleDataManager.php';

try {
    $choice = prompt("Choose database (mysql): ");

    if ($choice === 'mysql') {
        $mysqlConfig = [
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS']
        ];

        $mysqlExecutor = new DirectSQLExecutor('mysql', $mysqlConfig);
        $mysqlExecutor->executeSQLFile(__DIR__ . '/../mysql.sql');

        echo "MySQL execution completed.\n";
    }
    //  elseif ($choice === 'sqlite') {
    //     $sqliteConfig = [
    //         'database' => __DIR__ . '/../../' . $_ENV['DB_NAME'] . '.sqlite'
    //     ];

    //     $sqliteExecutor = new DirectSQLExecutor('sqlite', $sqliteConfig);
    //     $sqliteExecutor->executeSQLFile(__DIR__ . '/../sqlite.sql');

    //     echo "SQLite execution completed.\n";
    // } 
    else {
        echo "Invalid option. Please type 'mysql'.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
