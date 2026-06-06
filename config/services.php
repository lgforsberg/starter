<?php

declare(strict_types=1);

use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Middleware\CsrfMiddleware;
use App\Middleware\SessionMiddleware;
use App\Support\Container;
use App\Support\Csrf;
use App\Support\DatabaseFactory;
use App\Support\Session;
use App\Support\View;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

$container = new Container();

$dbConfig = require __DIR__ . '/database.php';

$container->set(Session::class, function () {
    return new Session();
});

$container->set(Csrf::class, function ($c) {
    return new Csrf($c->get(Session::class));
});

$container->set(View::class, function () {
    return new View(__DIR__ . '/../app/Views');
});

$container->set(LoggerInterface::class, function () {
    $logger = new Logger('app');
    $logger->pushHandler(
        new StreamHandler(__DIR__ . '/../storage/logs/app.log', Logger::INFO)
    );
    return $logger;
});

$container->set(\PDO::class, function () use ($dbConfig) {
    return DatabaseFactory::create($dbConfig);
});

// Middleware
$container->set(SessionMiddleware::class, function ($c) {
    return new SessionMiddleware($c->get(Session::class));
});

$container->set(CsrfMiddleware::class, function ($c) {
    return new CsrfMiddleware($c->get(Session::class), $c->get(Csrf::class));
});

// Controllers
$container->set(HomeController::class, function ($c) {
    return new HomeController($c->get(View::class));
});

$container->set(ContactController::class, function ($c) {
    return new ContactController(
        $c->get(View::class),
        $c->get(Session::class),
        $c->get(Csrf::class),
        $c->get(LoggerInterface::class),
    );
});

return $container;
