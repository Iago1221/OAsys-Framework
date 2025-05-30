<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Log;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Log\Log;

class LogRepository extends Repository
{
    protected function getModelClass(): string
    {
        return Log::class;
    }

    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function getTableName(): string
    {
        return 'logs';
    }
}