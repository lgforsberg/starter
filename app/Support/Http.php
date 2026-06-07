<?php

declare(strict_types=1);

namespace App\Support;

use Psr\Http\Message\ResponseInterface;

final class Http
{
    public static function redirect(ResponseInterface $response, string $to, int $status = 302): ResponseInterface
    {
        return $response
            ->withHeader('Location', $to)
            ->withStatus($status);
    }

    public static function json(ResponseInterface $response, array $data, int $status = 200): ResponseInterface
    {
        $response = $response->withStatus($status)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR));
        return $response;
    }
}
