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
        echo "Generating proxy for: $className\n";

        // Trigger proxy generation
        $em->getProxyFactory()->getProxy($className, ['id' => 1]);
    }

    echo "Proxy generation completed successfully!\n";
} catch (Exception $e) {
    echo "Error generating proxies: " . $e->getMessage() . "\n";
    exit(1);
}
