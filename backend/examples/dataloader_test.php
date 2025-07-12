<?php

/**
 * Example file to demonstrate DataLoader in action
 * 
 * This file simulates GraphQL queries that would benefit from DataLoader
 */

require_once __DIR__ . '/../vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Error\DebugFlag;
use App\GraphQL\GraphQLSchema;
use App\Database\DatabaseManager;
use App\DataLoader\UserLoader;
use App\Entity\User;

// Initialize database
$db = new DatabaseManager();

try {
    // Create schema if it doesn't exist
    if (!$db->getEntityManager()->getConnection()->isConnected()) {
        $db->createSchema();
    }

    // Create some test users if they don't exist
    $em = $db->getEntityManager();
    $userRepo = $em->getRepository(User::class);

    if (count($userRepo->findAll()) === 0) {
        echo "Creating test users...\n";
        $users = [
            ['name' => 'Alice', 'email' => 'alice@example.com'],
            ['name' => 'Bob', 'email' => 'bob@example.com'],
            ['name' => 'Charlie', 'email' => 'charlie@example.com'],
            ['name' => 'Diana', 'email' => 'diana@example.com'],
            ['name' => 'Eve', 'email' => 'eve@example.com'],
        ];

        foreach ($users as $userData) {
            $user = new User($userData['name'], $userData['email']);
            $em->persist($user);
        }
        $em->flush();
        echo "Test users created.\n";
    }

    // Setup context with DataLoader
    $context = [
        'db' => $db,
        'userLoader' => new UserLoader($db),
    ];

    // Build GraphQL schema
    $schema = GraphQLSchema::build();

    // Example 1: Query a single user
    echo "\nExample 1: Query a single user\n";
    $query = '
        query {
            user(id: 1) {
                id
                name
                email
            }
        }
    ';

    $result = GraphQL::executeQuery($schema, $query, null, $context);
    echo json_encode($result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE), JSON_PRETTY_PRINT) . "\n";

    // Example 2: Query multiple users with DataLoader
    echo "\nExample 2: Query multiple users with DataLoader\n";
    $query = '
        query {
            usersByIds(ids: [1, 2, 3]) {
                id
                name
                email
            }
        }
    ';

    $result = GraphQL::executeQuery($schema, $query, null, $context);
    echo json_encode($result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE), JSON_PRETTY_PRINT) . "\n";

    // Example 3: Query users with pagination
    echo "\nExample 3: Query users with pagination\n";
    $query = '
        query {
            users(limit: 3, offset: 0) {
                id
                name
                email
            }
        }
    ';

    $result = GraphQL::executeQuery($schema, $query, null, $context);
    echo json_encode($result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE), JSON_PRETTY_PRINT) . "\n";

    // Example 4: Create a new user
    echo "\nExample 4: Create a new user\n";
    $query = '
        mutation {
            createUser(input: {
                name: "Frank",
                email: "frank@example.com"
            }) {
                id
                name
                email
            }
        }
    ';

    $result = GraphQL::executeQuery($schema, $query, null, $context);
    echo json_encode($result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE), JSON_PRETTY_PRINT) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
