<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\Csrf;
use App\Support\Honeypot;
use App\Support\Http;
use App\Support\RateLimiter;
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
        private RateLimiter $rateLimiter,
        private Honeypot $honeypot,
    ) {}

    public function show(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->view->render($response, 'pages/contact', [
            'title' => 'Contact',
            'csrf' => $this->csrf,
            'honeypot' => $this->honeypot,
            'errors' => [],
            'old' => [],
            'success' => $this->session->getFlash('success'),
        ]);
    }

    public function store(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $request->getParsedBody() ?? [];

        if ($this->honeypot->isSpam($body)) {
            return Http::redirect($response, '/contact');
        }

        $ip = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';
        $rateLimitKey = 'contact:' . $ip;

        if ($this->rateLimiter->tooManyAttempts($rateLimitKey, 5, 900)) {
            return $this->view->render($response->withStatus(429), 'pages/contact', [
                'title' => 'Contact',
                'csrf' => $this->csrf,
                'honeypot' => $this->honeypot,
                'errors' => ['_rate' => 'Too many attempts. Please try again later.'],
                'old' => [],
                'success' => null,
            ]);
        }

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
                'honeypot' => $this->honeypot,
                'errors' => $errors,
                'old' => ['name' => $name, 'email' => $email, 'message' => $message],
                'success' => null,
            ]);
        }

        $this->rateLimiter->hit($rateLimitKey, 900);

        $this->logger->info('Contact form submitted', [
            'name' => $name,
            'email' => $email,
        ]);

        $this->session->flash('success', 'Thanks for your message! We\'ll be in touch.');

        return Http::redirect($response, '/contact');
    }
}
