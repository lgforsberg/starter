<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// Load config
$config = require __DIR__ . '/../config/app.php';

// Build container and services
$container = require __DIR__ . '/../config/services.php';

// Create Slim app with our container
AppFactory::setContainer($container);
$app = AppFactory::create();

// Middleware
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(
    $config['debug'],
    true,
    true,
);

$errorMiddleware->setDefaultErrorHandler(
    new App\Middleware\ErrorHandler($container, $config)
);

// Routes
require __DIR__ . '/../app/routes.php';

// Run
$app->run();
