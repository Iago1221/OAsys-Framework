<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Log;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Log\ErrorLog;
use Framework\Interface\Infrastructure\Persistence\Core\RotaRepository;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

class ErrorLogRepository extends Repository
{
    protected function queryBuilder()
    {
        parent::queryBuilder();
        $this->addJoin('oasys','usuarios', 'usuario', 'id', 'INNER', 'usuario');
        $this->addJoin('oasys','rotas', 'rota', 'id', 'INNER', 'rota');
        $this->with(['rota', 'usuario']);
    }

    protected function getModelClass(): string
    {
        return ErrorLog::class;
    }

    protected function loadUsuario(ErrorLog $model)
    {
        $this->hasOne($model, 'usuario', 'id', new UsuarioRepository($this->pdo), 'usuario');
    }

    protected function loadRota(ErrorLog $model)
    {
        $this->hasOne($model, 'rota', 'id', new RotaRepository($this->pdo), 'rota');
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
