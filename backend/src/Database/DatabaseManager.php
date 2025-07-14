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
                'driver' => 'pdo_sqlite',
                'path' => __DIR__ . '/../../' . $_ENV['DB_NAME'] . '.sqlite',
            ];

            $connection = DriverManager::getConnection($connectionParams);

            self::$entityManager = new EntityManager($connection, $config);
        }

        return self::$entityManager;
    }

    public static function createSchema(): void
    {
        $em = self::getEntityManager();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $classes = $em->getMetadataFactory()->getAllMetadata();
        $tool->createSchema($classes);
    }
}
