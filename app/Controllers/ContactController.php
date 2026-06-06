<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\Csrf;
use App\Support\Session;
use App\Support\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

final class ContactController
{
    public function __construct(
        private View $view,
        private Session $session,
        private Csrf $csrf,
        private LoggerInterface $logger,
    ) {}

    public function show(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->view->render($response, 'pages/contact', [
            'title' => 'Contact',
            'csrf' => $this->csrf,
            'errors' => [],
            'old' => [],
            'success' => $this->session->getFlash('success'),
        ]);
    }

    public function store(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $request->getParsedBody() ?? [];

        $name = trim($body['name'] ?? '');
        $email = trim($body['email'] ?? '');
        $message = trim($body['message'] ?? '');

        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'A valid email is required.';
        }

        if ($message === '') {
            $errors['message'] = 'Message is required.';
        }

        if (!empty($errors)) {
            return $this->view->render($response->withStatus(422), 'pages/contact', [
                'title' => 'Contact',
                'csrf' => $this->csrf,
                'errors' => $errors,
                'old' => ['name' => $name, 'email' => $email, 'message' => $message],
                'success' => null,
            ]);
        }

        $this->logger->info('Contact form submitted', [
            'name' => $name,
            'email' => $email,
        ]);

        $this->session->flash('success', 'Thanks for your message! We\'ll be in touch.');

        return $response
            ->withHeader('Location', '/contact')
            ->withStatus(302);
    }
}
