<?php

namespace Framework\Infrastructure\DynamicReport;

/**
 * Metadados de um campo permitido em relatório dinâmico (whitelist).
 */
class DynamicReportFieldSchema
{
    /**
     * @param string[] $filterOperators Operadores aceitos para filtro (ex.: IGUAL, CONTEM)
     * @param string[] $requiresWith Relacionamentos do repositório raiz necessários para exibir/filtrar
     * @param array<int|string, string>|null $displayValueMap Mapeia valor bruto (ex.: int de const) para rótulo na saída
     */
    public function __construct(
        private string $key,
        private string $label,
        private ?string $filterColumn,
        private array $filterOperators,
        private bool $sortable,
        private ?string $sortColumn,
        private array $requiresWith = [],
        private ?array $displayValueMap = null
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getFilterColumn(): ?string
    {
        return $this->filterColumn;
    }

    /** @return string[] */
    public function getFilterOperators(): array
    {
        return $this->filterOperators;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function getSortColumn(): ?string
    {
        return $this->sortColumn;
    }

    /** @return string[] */
    public function getRequiresWith(): array
    {
        return $this->requiresWith;
    }

    public function isFilterable(): bool
    {
        return $this->filterColumn !== null && $this->filterOperators !== [];
    }

    /** @return array<int|string, string>|null */
    public function getDisplayValueMap(): ?array
    {
        return $this->displayValueMap;
    }
}
