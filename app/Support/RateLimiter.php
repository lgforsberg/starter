<?php

declare(strict_types=1);

namespace App\Support;

final class RateLimiter
{
    private string $storagePath;

    public function __construct(string $storagePath)
    {
        $this->storagePath = rtrim($storagePath, '/');
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0775, true);
        }
    }

    public function tooManyAttempts(string $key, int $max, int $decaySeconds): bool
    {
        $attempts = $this->getAttempts($key, $decaySeconds);
        return count($attempts) >= $max;
    }

    public function hit(string $key, int $decaySeconds): void
    {
        $file = $this->filePath($key);
        $attempts = $this->getAttempts($key, $decaySeconds);
        $attempts[] = time();
        file_put_contents($file, json_encode($attempts), LOCK_EX);
    }

    public function clear(string $key): void
    {
        $file = $this->filePath($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    private function getAttempts(string $key, int $decaySeconds): array
    {
        $file = $this->filePath($key);
        if (!file_exists($file)) {
            return [];
        }

        $attempts = json_decode(file_get_contents($file), true) ?? [];
        $cutoff = time() - $decaySeconds;

        return array_values(array_filter($attempts, fn(int $ts) => $ts > $cutoff));
    }

    private function filePath(string $key): string
    {
        return $this->storagePath . '/' . md5($key) . '.json';
    }
}
