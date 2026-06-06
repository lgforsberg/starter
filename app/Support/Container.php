<?php

declare(strict_types=1);

namespace App\Support;

use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    private array $factories = [];
    private array $resolved = [];

    public function set(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    public function get(string $id): mixed
    {
        if (!isset($this->resolved[$id])) {
            if (!$this->has($id)) {
                throw new \RuntimeException("Service not found: {$id}");
            }
            $this->resolved[$id] = ($this->factories[$id])($this);
        }

        return $this->resolved[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->factories[$id]);
    }
}
