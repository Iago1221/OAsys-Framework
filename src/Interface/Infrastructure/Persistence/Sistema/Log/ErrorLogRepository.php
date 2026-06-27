<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Log;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Log\ErrorLog;

class ErrorLogRepository extends Repository
{
    protected function getModelClass(): string
    {
        return ErrorLog::class;
    }

    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function getTableName(): string
    {
        return 'error_logs';
    }
}
