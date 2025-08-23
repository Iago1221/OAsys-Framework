<?php

namespace Framework\Infrastructure\Tenant;

use Framework\Core\Main;

class TenantLoader
{
    private static $configs = [];

    public static function load($configs)
    {
        self::$configs = $configs;
    }

    public static function listarTodos(): array
    {

        return self::$configs;
    }

    public static function conectar(array $tenant)
    {
        Main::switchConnection($tenant);
    }
}
