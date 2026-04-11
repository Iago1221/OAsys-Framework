<?php

namespace Framework\Infrastructure\DynamicReport;

/**
 * Registra esquemas de relatórios dinâmicos por pacote (módulo ERP).
 */
interface DynamicReportSchemaProviderInterface
{
    /** @return DynamicReportEntitySchema[] */
    public function getEntitySchemas(): array;
}
