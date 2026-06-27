<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Log;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Log\RouteLog;

class RouteLogRepository extends Repository
{
    protected function getModelClass(): string
    {
        return RouteLog::class;
    }

    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function getTableName(): string
    {
        return 'route_logs';
    }
}
