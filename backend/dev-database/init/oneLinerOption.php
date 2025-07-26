<?php

require_once __DIR__ . '/../../bootstrap.php';
include_once __DIR__ . '/../ExampleDataManager.php';

function runMySQLFile($host, $port, $user, $pass, $db, $file)
{
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->exec(file_get_contents($file));
    echo "MySQL file executed!\n";
}

// function runSQLiteFile($dbPath, $file)
// {
//     $pdo = new PDO("sqlite:$dbPath");
//     $pdo->exec(file_get_contents($file));
//     echo "SQLite file executed!\n";
// }

try {
    $choice = prompt("Choose database (mysql): ");

    if ($choice === 'mysql') {
        runMySQLFile($_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME'], __DIR__ . '/../mysql.sql');
    }
    //  elseif ($choice === 'sqlite') {
    //     runSQLiteFile(__DIR__ . '/../../' . $_ENV['DB_NAME'] . '.sqlite', __DIR__ . '/../sqlite.sql');
    // }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
