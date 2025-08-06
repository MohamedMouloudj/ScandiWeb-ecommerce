<?php

namespace App\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class DatabaseManager
{
    private static ?EntityManager $entityManager = null;

    public static function getEntityManager(): EntityManager
    {
        if (self::$entityManager === null) {
            $isDevMode = ($_ENV['APP_ENV'] ?? 'development') !== 'production';

            // Proxy classes directory 
            $proxyDir = dirname(__DIR__, 2) . '/var/cache/doctrine/proxies';

            if (!is_dir($proxyDir)) {
                mkdir($proxyDir, 0755, true);
            }

            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [__DIR__ . '/../Entity'],
                isDevMode: $isDevMode,
                proxyDir: $proxyDir,
                cache: $isDevMode ? null : new \Symfony\Component\Cache\Adapter\ArrayAdapter()
            );

            // Proxy generation strategy based on environment
            if ($isDevMode) {
                // Development: Generate proxies on-demand (this uses eval(), so there is no saved proxy classes)
                $config->setAutoGenerateProxyClasses(\Doctrine\ORM\Proxy\ProxyFactory::AUTOGENERATE_EVAL);
            } else {
                // Production: Generate only if file doesn't exist
                $config->setAutoGenerateProxyClasses(\Doctrine\ORM\Proxy\ProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);
            }

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
