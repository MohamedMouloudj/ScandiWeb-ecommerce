<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NamedType;
use App\GraphQL\Types\NodeInterface;
use App\Entity\User;

class Types
{
    /** @var array<string, Type&NamedType> */
    private static array $types = [];

    /**
     * @return Type&NamedType
     */
    public static function load(string $typeName): Type
    {
        if (isset(self::$types[$typeName])) {
            return self::$types[$typeName];
        }

        $methodName = lcfirst($typeName);

        if (!method_exists(self::class, $methodName)) {
            throw new \Exception("Unknown GraphQL type: {$typeName}");
        }

        $type = self::{$methodName}();
        if (is_callable($type)) {
            $type = $type();
        }

        return self::$types[$typeName] = $type;
    }

    public static function user(): callable
    {
        return fn() => self::$types['User'] ??= new ObjectType([
            'name' => 'User',
            'description' => 'User entity',
            'interfaces' => [NodeInterface::get()], // Implement Node interface
            'fields' => fn() => [
                'id' => [
                    'type' => Type::nonNull(Type::id()),
                    'description' => 'User ID',
                    'resolve' => fn(User $user) => $user->getId(),
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'User name',
                    'resolve' => fn(User $user) => $user->getName(),
                ],
                'email' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'User email address',
                    'resolve' => fn(User $user) => $user->getEmail(),
                ],
                'createdAt' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'User creation timestamp',
                    'resolve' => fn(User $user) => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                ],
                'isActive' => [
                    'type' => Type::nonNull(Type::boolean()),
                    'description' => 'Whether user is active',
                    'resolve' => fn(User $user) => $user->isActive(),
                ],
            ],
        ]);
    }

    public static function userInput(): callable
    {
        return fn() => self::$types['UserInput'] ??= new InputObjectType([
            'name' => 'UserInput',
            'description' => 'User input for creating/updating users',
            'fields' => [
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'User name',
                    'resolve' => fn(User $user) => $user->getName(),
                ],
                'email' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'User email address',
                    'resolve' => fn(User $user) => $user->getEmail(),
                ],
                'isActive' => [
                    'type' => Type::boolean(),
                    'description' => 'Whether user is active',
                    'resolve' => fn(User $user) => $user->isActive(),
                    'defaultValue' => true,
                ],
            ],
        ]);
    }
}
