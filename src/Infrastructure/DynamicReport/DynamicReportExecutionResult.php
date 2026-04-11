<?php

namespace Framework\Infrastructure\DynamicReport;

/**
 * Resultado tabular de um relatório dinâmico (cabeçalhos + linhas).
 */
class DynamicReportExecutionResult
{
    /**
     * @param array<int, array{key: string, label: string}> $columns
     * @param array<int, array<string, mixed>> $rows
     */
    public function __construct(
        private array $columns,
        private array $rows
    ) {
    }

    /** @return array<int, array{key: string, label: string}> */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /** @return array<int, array<string, mixed>> */
    public function getRows(): array
    {
        return $this->rows;
    }
}
