<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

return [
    'default' => $_ENV['CACHE_DRIVER'],

    'stores' => [
        'file' => [
            'driver' => 'file',
            'path' => __DIR__ . '/../storage/cache/data',
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
        ],
        'memory' => [
            'driver' => 'array',
        ],
    ],
];
