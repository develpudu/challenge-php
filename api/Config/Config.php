<?php

declare(strict_types=1);

namespace App\Config;

final class Config
{
    private static ?self $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }
        return $value;
    }

    public function getRequired(string $key): string
    {
        $value = $this->get($key);
        if ($value === null || $value === '') {
            throw new \RuntimeException("Falta la variable de entorno: {$key}");
        }
        return $value;
    }

    public function getPrecisionUsd(): float
    {
        $value = $this->get('PRECIO_USD', '0');
        $parsed = (float) $value;
        if ($parsed <= 0) {
            throw new \RuntimeException('PRECIO_USD debe ser un número positivo');
        }
        return $parsed;
    }

    public function getDbConfig(): array
    {
        return [
            'host' => $this->getRequired('DB_HOST'),
            'port' => $this->get('DB_PORT', '3306'),
            'database' => $this->getRequired('DB_NAME'),
            'username' => $this->getRequired('DB_USER'),
            'password' => $this->getRequired('DB_PASS'),
        ];
    }
}
