<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Log;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Log\RouteLog;
use Framework\Interface\Infrastructure\Persistence\Core\RotaRepository;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

class RouteLogRepository extends Repository
{
    protected function queryBuilder()
    {
        parent::queryBuilder();
        $this->addJoin('oasys','usuarios', 'usuario', 'id', 'INNER', 'usuario');
        $this->addJoin('oasys','rotas', 'rota', 'id', 'INNER', 'rota');
        $this->with(['rota', 'usuario']);
    }

    protected function loadUsuario(RouteLog $model)
    {
        $this->hasOne($model, 'usuario', 'id', new UsuarioRepository($this->pdo), 'usuario');
    }

    protected function loadRota(RouteLog $model)
    {
        $this->hasOne($model, 'rota', 'id', new RotaRepository($this->pdo), 'rota');
    }

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
