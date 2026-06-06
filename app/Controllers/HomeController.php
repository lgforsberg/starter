<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class HomeController
{
    public function __construct(
        private View $view,
    ) {}

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->view->render($response, 'pages/home', [
            'title' => 'Home',
        ]);
    }

    public function about(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->view->render($response, 'pages/about', [
            'title' => 'About',
        ]);
    }

    public function htmxDemo(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->view->isHtmx($request)) {
            return $this->view->renderFragment($response, 'fragments/greeting', [
                'name' => 'World',
                'time' => date('H:i:s'),
            ]);
        }

        return $this->view->render($response, 'pages/home', [
            'title' => 'Home',
        ]);
    }
}
