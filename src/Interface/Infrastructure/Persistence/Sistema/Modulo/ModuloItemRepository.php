<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Modulo;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Modulo\ModuloItem;
use Framework\Interface\Infrastructure\Persistence\Core\RotaRepository;

class ModuloItemRepository extends Repository
{
    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function queryBuilder()
    {
        parent::queryBuilder();
        $this->with(['rota']);
    }

    protected function loadModulo(ModuloItem $item)
    {
        $this->belongsTo($item, 'Modulo', 'id', 'modulo', new ModuloRepository($this->pdo));
    }

    protected function loadRota(ModuloItem $item)
    {
        $this->hasOne($item, 'Rota', 'id', new RotaRepository($this->pdo), 'rota');
    }

    public function getTableName(): string
    {
        return 'modulo_itens';
    }

    public function getModelClass(): string
    {
        return ModuloItem::class;
    }
}