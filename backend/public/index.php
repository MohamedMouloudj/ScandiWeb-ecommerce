<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use FastRoute\RouteCollector;
use App\Controller\GraphQLController;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->post('/graphQL', [GraphQLController::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(['error' => '404 Not Found']);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode(['error' => '405 Method Not Allowed']);
        break;
    case FastRoute\Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
        try {
            $controller = new $class();
            $response = $controller->$method($vars);
            if ($response !== null) {
                echo $response;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Internal Server Error',
                'message' => 'An error occurred while processing your request'
            ]);
            // error_log("Controller error: " . $e->getMessage() . " " . $e->getTraceAsString());
        }
        break;
}
