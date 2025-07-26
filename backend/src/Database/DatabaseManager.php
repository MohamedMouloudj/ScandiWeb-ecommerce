<?php

namespace App\Database;

use Dotenv\Dotenv;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class DatabaseManager
{
    private static ?EntityManager $entityManager = null;

    public static function getEntityManager(): EntityManager
    {
        if (self::$entityManager === null) {
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [__DIR__ . '/../Entity'],
                isDevMode: true,
            );

            $connectionParams = [
                'driver'   => 'pdo_mysql',
                'host'     => $_ENV['DB_HOST'] ?? 'localhost',
                'port'     => $_ENV['DB_PORT'] ?? 3306,
                'dbname'   => $_ENV['DB_NAME'],
                'user'     => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'charset'  => 'utf8mb4',
            ];

            $connection = DriverManager::getConnection($connectionParams);

            self::$entityManager = new EntityManager($connection, $config);
        }

        return self::$entityManager;
    }
}
