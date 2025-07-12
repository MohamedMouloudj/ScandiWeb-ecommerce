<?php

namespace App\GraphQL;

use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Types\Types;
use App\GraphQL\Resolvers\UserResolver;

class GraphQLSchema
{
    private static ?Schema $schema = null;

    public static function build(): Schema
    {
        if (self::$schema === null) {
            self::$schema = new Schema([
                'query' => self::buildQueryType(),
                'mutation' => self::buildMutationType(),
                'typeLoader' => [Types::class, 'load'], // For lazy loading
            ]);
        }

        return self::$schema;
    }

    private static function buildQueryType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => [
                'user' => [
                    'type' => Types::user(),
                    'args' => [
                        'id' => Type::nonNull(Type::id()),
                    ],
                    'resolve' => [UserResolver::class, 'getUser'],
                ],
                'users' => [
                    'type' => Type::listOf(Types::user()),
                    'args' => [
                        'limit' => [
                            'type' => Type::int(),
                            'defaultValue' => 10,
                        ],
                        'offset' => [
                            'type' => Type::int(),
                            'defaultValue' => 0,
                        ],
                    ],
                    'resolve' => [UserResolver::class, 'getUsers'],
                ],
                // Add a new field to demonstrate DataLoader with multiple IDs
                'usersByIds' => [
                    'type' => Type::listOf(Types::user()),
                    'args' => [
                        'ids' => Type::nonNull(Type::listOf(Type::id())),
                    ],
                    'resolve' => function ($root, array $args, $context) {
                        $ids = array_map('intval', $args['ids']);
                        return $context['userLoader']->loadMany($ids);
                    },
                ],
            ],
        ]);
    }

    private static function buildMutationType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'createUser' => [
                    'type' => Types::user(),
                    'args' => [
                        'input' => Type::nonNull(Types::userInput()),
                    ],
                    'resolve' => [UserResolver::class, 'createUser'],
                ],
                'updateUser' => [
                    'type' => Types::user(),
                    'args' => [
                        'id' => Type::nonNull(Type::id()),
                        'input' => Type::nonNull(Types::userInput()),
                    ],
                    'resolve' => [UserResolver::class, 'updateUser'],
                ],
                'deleteUser' => [
                    'type' => Type::boolean(),
                    'args' => [
                        'id' => Type::nonNull(Type::id()),
                    ],
                    'resolve' => [UserResolver::class, 'deleteUser'],
                ],
            ],
        ]);
    }
}
