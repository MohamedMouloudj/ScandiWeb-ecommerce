<?php

namespace App\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class DatabaseManager
{
    private static ?EntityManager $entityManager = null;

    public static function getEntityManager(): EntityManager
    {
        if (self::$entityManager === null) {
            // Setup Doctrine ORM
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [__DIR__ . '/../Entity'],
                isDevMode: true,
                proxyDir: null,
                cache: new ArrayAdapter()
            );

            // Database connection parameters
            $connectionParams = [
                'driver' => 'pdo_sqlite',
                'path' => __DIR__ . '/../../database.sqlite',
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
