<?php

require_once __DIR__ . '../../vendor/autoload.php';
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Config\Database;

//Instancia de illuminate
new Database();

$app = AppFactory::create();
$app->setBasePath("/Programacion3/Parciales/segundoParcialProgra3/public");

// Add Routing Middleware
$app->addRoutingMiddleware();

// Define Custom Error Handler
$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $payload = ['error' => $exception->getMessage()];

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    return $response;
};

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

//REGISTRAR RUTAS
(require_once __DIR__ . './routes.php')($app);

//REGISTRAR MIDDLEWARE
(require_once __DIR__ . './middlewares.php')($app);

return $app;