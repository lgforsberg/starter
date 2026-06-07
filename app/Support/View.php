<?php

declare(strict_types=1);

namespace App\Support;

use Psr\Http\Message\ResponseInterface;

final class View
{
    private string $basePath;
    private string $baseUrl;
    private ?string $layoutFile = null;
    private array $layoutData = [];

    public function __construct(string $basePath, string $baseUrl = '')
    {
        $this->basePath = rtrim($basePath, '/');
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function render(ResponseInterface $response, string $template, array $data = []): ResponseInterface
    {
        $html = $this->renderTemplate($template, $data);

        if ($this->layoutFile) {
            $layoutFile = $this->layoutFile;
            $layoutData = array_merge($data, $this->layoutData);
            $this->layoutFile = null;
            $this->layoutData = [];

            $layoutData['content'] = $html;
            $html = $this->renderTemplate($layoutFile, $layoutData);
        }

        $response->getBody()->write($html);
        return $response;
    }

    public function renderFragment(ResponseInterface $response, string $template, array $data = []): ResponseInterface
    {
        $html = $this->renderTemplate($template, $data);
        $response->getBody()->write($html);
        return $response;
    }

    private function renderTemplate(string $template, array $data): string
    {
        $file = $this->basePath . '/' . $template . '.php';

        if (!file_exists($file)) {
            throw new \RuntimeException("View not found: {$template}");
        }

        extract($data, EXTR_SKIP);
        $__view = $this;

        ob_start();
        require $file;
        return ob_get_clean();
    }

    public function layout(string $layout, array $data = []): void
    {
        $this->layoutFile = $layout;
        $this->layoutData = array_merge($this->layoutData, $data);
    }

    public function partial(string $template, array $data = []): void
    {
        echo $this->renderTemplate($template, $data);
    }

    public function url(string $path = ''): string
    {
        if ($path === '') {
            return $this->baseUrl;
        }
        return $this->baseUrl . '/' . ltrim($path, '/');
    }

    public function asset(string $path): string
    {
        return '/assets/' . ltrim($path, '/');
    }

    public function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    public function isHtmx(\Psr\Http\Message\ServerRequestInterface $request): bool
    {
        return $request->hasHeader('HX-Request');
    }
}
