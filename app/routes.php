<?php

declare(strict_types=1);

use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Middleware\CsrfMiddleware;
use App\Middleware\SessionMiddleware;
use Slim\App;

/** @var App $app */

// All routes use session middleware
$app->add($app->getContainer()->get(SessionMiddleware::class));

// Pages
$app->get('/', [HomeController::class, 'index']);
$app->get('/about', [HomeController::class, 'about']);

// Contact form
$app->get('/contact', [ContactController::class, 'show']);
$app->post('/contact', [ContactController::class, 'store'])
    ->add($app->getContainer()->get(CsrfMiddleware::class));

// HTMX fragment example
$app->get('/htmx/greeting', [HomeController::class, 'htmxDemo']);
