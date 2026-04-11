<?php

namespace Framework\Infrastructure\DynamicReport;

/**
 * Registro estático de esquemas declarados pelos providers da aplicação.
 */
class DynamicReportSchemaRegistry
{
    /** @var array<string, DynamicReportEntitySchema> */
    private static array $schemas = [];

    public static function registerProvider(DynamicReportSchemaProviderInterface $provider): void
    {
        foreach ($provider->getEntitySchemas() as $schema) {
            $key = self::key($schema->getPackage(), $schema->getRootEntityKey());
            self::$schemas[$key] = $schema;
        }
    }

    public static function clear(): void
    {
        self::$schemas = [];
    }

    public static function key(string $package, string $rootEntityKey): string
    {
        return $package . '::' . $rootEntityKey;
    }

    public static function getSchema(string $package, string $rootEntityKey): ?DynamicReportEntitySchema
    {
        return self::$schemas[self::key($package, $rootEntityKey)] ?? null;
    }

    /**
     * @return DynamicReportEntitySchema[]
     */
    public static function listByPackage(string $package): array
    {
        $out = [];
        foreach (self::$schemas as $schema) {
            if (strcasecmp($schema->getPackage(), $package) === 0) {
                $out[] = $schema;
            }
        }

        return $out;
    }

    /**
     * @return DynamicReportEntitySchema[]
     */
    public static function all(): array
    {
        return array_values(self::$schemas);
    }
}
