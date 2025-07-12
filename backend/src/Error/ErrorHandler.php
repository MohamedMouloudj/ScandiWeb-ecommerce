<?php
// src/GraphQL/Error/ErrorHandler.php
namespace App\GraphQL\Error;

use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use Psr\Log\LoggerInterface;

class ErrorHandler
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handleErrors(array $errors, callable $formatter): array
    {
        foreach ($errors as $error) {
            $this->logError($error);
        }

        return array_map($formatter, $errors);
    }

    private function logError(Error $error): void
    {
        $this->logger->error('GraphQL Error', [
            'message' => $error->getMessage(),
            'locations' => $error->getLocations(),
            'path' => $error->getPath(),
            'trace' => $error->getTraceAsString()
        ]);
    }

    public function formatError(Error $error): array
    {
        return FormattedError::createFromException($error);
    }
}
