<?php

namespace Framework\Infrastructure\ENV;

use Dotenv\Dotenv;

abstract class EnvUtils
{
    public function __construct()
    {
        self::loadFromPath($this->getPath());
    }

    public static function loadFromPath($path)
    {
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
    }

    protected static function get($parameter)
    {
        if (isset($_ENV)) {
            return $_ENV[$parameter] ?? null;
        }

        throw new \BadMethodCallException("Environment not loaded");
    }

    /**
     * Retornar
     * DEV: ambiente de desenvolvimento
     * QA: ambiente de qualidade/homologação/sandbox
     * PROD: ambiente de produção
     * @return string
     */
    abstract public function getAmbiente(): string;
    abstract public function getPath(): string;
}
