<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Relatorio;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Relatorio\Relatorio;

class RelatorioRepository extends Repository
{
    protected function getModelClass(): string
    {
        return Relatorio::class;
    }

    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function getTableName(): string
    {
        return 'relatorios';
    }
}