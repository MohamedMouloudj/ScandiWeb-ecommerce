<?php

namespace App\Error;

use GraphQL\Error\ClientAware;

abstract class GraphQLException extends \Exception implements ClientAware
{
    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return 'user';
    }
}

class ValidationException extends GraphQLException
{
    public function getCategory(): string
    {
        return 'validation';
    }
}

class NotFoundException extends GraphQLException
{
    public function getCategory(): string
    {
        return 'not_found';
    }
}
