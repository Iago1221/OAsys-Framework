<?php

namespace Framework\Infrastructure\DynamicReport;

/**
 * Esquema de uma fonte de dados (entidade raiz) para relatório dinâmico.
 */
class DynamicReportEntitySchema
{
    /** @param array<string, DynamicReportFieldSchema> $fields */
    public function __construct(
        private string $package,
        private string $rootEntityKey,
        private string $repositoryClass,
        private array $fields,
        private int $defaultLimit = 100,
        private int $maxLimit = 500
    ) {
    }

    public function getPackage(): string
    {
        return $this->package;
    }

    public function getRootEntityKey(): string
    {
        return $this->rootEntityKey;
    }

    public function getRepositoryClass(): string
    {
        return $this->repositoryClass;
    }

    /** @return array<string, DynamicReportFieldSchema> */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function getField(string $key): ?DynamicReportFieldSchema
    {
        return $this->fields[$key] ?? null;
    }

    public function getDefaultLimit(): int
    {
        return $this->defaultLimit;
    }

    public function getMaxLimit(): int
    {
        return $this->maxLimit;
    }

    /**
     * @param string[] $selectedFieldKeys
     * @param array<int, array{name: string, operator: string, value: mixed}> $filters
     * @return string[]
     */
    public function resolveRequiredWith(array $selectedFieldKeys, array $filters): array
    {
        $with = [];
        foreach ($selectedFieldKeys as $key) {
            $f = $this->getField($key);
            if ($f) {
                $with = array_merge($with, $f->getRequiresWith());
            }
        }
        foreach ($filters as $filter) {
            $name = $filter['name'] ?? '';
            $f = $this->getField($name);
            if ($f) {
                $with = array_merge($with, $f->getRequiresWith());
            }
        }

        return array_values(array_unique($with));
    }
}
