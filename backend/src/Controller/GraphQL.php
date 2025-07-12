<?php

namespace App\Controller;

use App\GraphQL\GraphQLSchema;
use App\Database\DatabaseManager;
use App\DataLoader\UserLoader;
use GraphQL\GraphQL as GraphQLClass;
use GraphQL\Error\DebugFlag;

class GraphQL
{
    public function handle($vars)
    {
        $db = new DatabaseManager();
        $context = [
            'db' => $db,
            'userLoader' => new UserLoader($db),
        ];

        $input = json_decode(file_get_contents('php://input'), true);
        $query = $input['query'] ?? '';
        $variables = $input['variables'] ?? [];

        try {
            $schema = GraphQLSchema::build();
            $result = GraphQLClass::executeQuery(
                $schema,
                $query,
                null,
                $context,
                $variables
            );
            header('Content-Type: application/json');
            return json_encode($result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE));
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode([
                'errors' => [
                    ['message' => $e->getMessage()]
                ]
            ]);
        }
    }
}
