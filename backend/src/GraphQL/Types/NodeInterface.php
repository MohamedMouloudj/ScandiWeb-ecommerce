<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;
use App\Entity\User;
use App\GraphQL\Types\Types;

class NodeInterface
{
    private static ?InterfaceType $interface = null;

    public static function get(): InterfaceType
    {
        if (self::$interface === null) {
            self::$interface = new InterfaceType([
                'name' => 'Node',
                'description' => 'An object with an ID',
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::id()),
                        'description' => 'The ID of the object',
                    ],
                ],
                'resolveType' => function ($value) {
                    if ($value instanceof User) {
                        return Types::user();
                    }
                    throw new \Exception("Unknown type for Node interface");
                },
            ]);
        }

        return self::$interface;
    }
}
