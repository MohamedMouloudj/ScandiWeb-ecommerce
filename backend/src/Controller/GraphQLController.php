<?php

namespace App\Controller;

use App\Database\DatabaseManager;
use App\Database\DataLoader\EcommerceDataLoaderManager;
use GraphQL\GraphQL;
use GraphQL\Utils\BuildSchema;
use GraphQL\Type\Schema;
use GraphQL\Error\DebugFlag;
use App\GraphQL\Resolvers\ResolverManager;

class GraphQLController
{
    private Schema $schema;
    private ResolverManager $resolverManager;

    public function __construct()
    {
        $this->initializeGraphQL();
    }

    private function initializeGraphQL(): void
    {
        $schemaSDL = file_get_contents(__DIR__ . '/../GraphQL/schema.graphql');

        $em = DatabaseManager::getEntityManager();
        $loaderManager = new EcommerceDataLoaderManager($em);

        $this->resolverManager = new ResolverManager($em, $loaderManager);

        $this->schema = BuildSchema::build($schemaSDL, function ($typeConfig) {
            $typeName = $typeConfig['name'] ?? null;
            $resolverMap = $this->resolverManager->getResolverMap();

            if ($typeName && isset($resolverMap[$typeName])) {
                $typeConfig['resolveField'] = function ($source, $args, $context, $info) use ($resolverMap, $typeName) {
                    $fieldName = $info->fieldName;

                    if (isset($resolverMap[$typeName][$fieldName])) {
                        $resolver = $resolverMap[$typeName][$fieldName];
                        return call_user_func($resolver, $source, $args, $context, $info);
                    }

                    // For scalar fields, try to resolve automatically using getter methods
                    if (is_object($source)) {
                        $getter = 'get' . ucfirst($fieldName);
                        if (method_exists($source, $getter)) {
                            return $source->$getter();
                        }

                        // Try is* for boolean fields
                        $isGetter = 'is' . ucfirst($fieldName);
                        if (method_exists($source, $isGetter)) {
                            return $source->$isGetter();
                        }
                    }

                    // If no resolver found
                    return null;
                };
            }
            return $typeConfig;
        });
    }

    public function handle($vars)
    {
        try {
            $input = $this->parseRequest();
            $context = $this->createContext();

            $result = GraphQL::executeQuery(
                $this->schema,
                $input['query'],
                null,
                $context,
                $input['variables'] ?? [],
                $input['operationName'] ?? null
            );

            header('Content-Type: application/json');

            return json_encode($result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE));
        } catch (\Exception $e) {
            return $this->handleError($e, 400);
        }
    }

    private function parseRequest(): array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON in request body');
        }

        if (!isset($input['query'])) {
            throw new \Exception('GraphQL query is required');
        }

        return $input;
    }

    private function createContext(): array
    {
        $db = new DatabaseManager();
        return [
            'db' => $db,
            'dataLoader' => new EcommerceDataLoaderManager($db->getEntityManager()),
        ];
    }

    private function handleError(\Exception $e, ?int $statusCode = 500): string
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        return json_encode([
            'errors' => [
                [
                    'message' => $e->getMessage(),
                    // 'trace' => $e->getTraceAsString()
                ]
            ]
        ]);
    }
}
