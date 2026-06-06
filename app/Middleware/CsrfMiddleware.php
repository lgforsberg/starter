<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Support\Csrf;
use App\Support\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

final class CsrfMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Session $session,
        private Csrf $csrf,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->session->start();

        $method = strtoupper($request->getMethod());

        if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'], true)) {
            $body = $request->getParsedBody();
            $token = $body['_csrf_token'] ?? '';

            if (!$this->csrf->validate($token)) {
                $response = new Response(403);
                $response->getBody()->write('Invalid CSRF token');
                return $response;
            }

            $this->csrf->regenerate();
        }

        return $handler->handle($request);
    }
}
