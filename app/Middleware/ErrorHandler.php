<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Support\View;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response;

final class ErrorHandler implements ErrorHandlerInterface
{
    public function __construct(
        private ContainerInterface $container,
        private array $config,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
        \Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
    ): ResponseInterface {
        $response = new Response();
        $view = $this->container->get(View::class);

        if ($logErrors) {
            $logger = $this->container->get(\Psr\Log\LoggerInterface::class);
            $logger->error($exception->getMessage(), [
                'exception' => $exception,
                'url' => (string) $request->getUri(),
            ]);
        }

        if ($exception instanceof HttpNotFoundException) {
            return $view->render(
                $response->withStatus(404),
                'pages/404',
                ['title' => 'Not Found']
            );
        }

        $data = ['title' => 'Server Error'];
        if ($this->config['debug']) {
            $data['exception'] = $exception;
        }

        return $view->render(
            $response->withStatus(500),
            'pages/500',
            $data
        );
    }
}
