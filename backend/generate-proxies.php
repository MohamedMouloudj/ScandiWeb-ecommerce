<?php

require_once __DIR__ . '/bootstrap.php';

use App\Database\DatabaseManager;

try {
    echo "Generating Doctrine proxy classes...\n";

    $em = DatabaseManager::getEntityManager();
    $metadataFactory = $em->getMetadataFactory();

    // Get all entity metadata
    $allMetadata = $metadataFactory->getAllMetadata();

    echo "Found " . count($allMetadata) . " entities to process.\n";

    // Generate proxies for all entities
    foreach ($allMetadata as $metadata) {
        $className = $metadata->getName();
        // Skip abstract classes, interfaces, and mapped superclasses, it can't be used to generate a proxy
        $refl = new ReflectionClass($className);
        if ($refl->isAbstract() || $refl->isInterface() || $metadata->isMappedSuperclass) {
            echo "Skipping non-concrete class: $className\n";
            continue;
        }
        // Trigger proxy generation
        echo "Generating proxy for: $className\n";
        $em->getProxyFactory()->getProxy($className, [$metadata->getIdentifier()[0] => 1]);
    }

    echo "Proxy generation completed successfully!\n";
} catch (Exception $e) {
    echo "Error generating proxies: " . $e->getMessage() . "\n";
    exit(1);
}
