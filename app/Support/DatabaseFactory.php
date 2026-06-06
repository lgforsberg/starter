<?php

declare(strict_types=1);

namespace App\Support;

use PDO;

final class DatabaseFactory
{
    public static function create(array $config): ?PDO
    {
        if (empty($config['host']) || empty($config['database'])) {
            return null;
        }

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;sslmode=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['sslmode'],
        );

        return new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
