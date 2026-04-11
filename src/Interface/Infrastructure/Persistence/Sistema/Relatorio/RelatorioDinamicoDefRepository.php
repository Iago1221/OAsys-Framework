<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Relatorio;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Relatorio\RelatorioDinamicoDef;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

class RelatorioDinamicoDefRepository extends Repository
{
    protected function queryBuilder(): void
    {
        parent::queryBuilder();
        $this->with(['usuario']);
    }

    public function loadUsuario(RelatorioDinamicoDef $model): void
    {
        $this->hasOne($model, 'usuario', 'id', new UsuarioRepository($this->pdo), 'usuarioId');
    }

    protected function getModelClass(): string
    {
        return RelatorioDinamicoDef::class;
    }

    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function getTableName(): string
    {
        return 'relatorio_dinamico_def';
    }
}
