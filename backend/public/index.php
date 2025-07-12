<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->post('/graphql', [App\Controller\GraphQL::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
        $controller = new $class();
        echo $controller->$method($vars);
        break;
}
