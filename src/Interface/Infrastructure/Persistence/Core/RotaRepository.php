<?php

namespace Framework\Interface\Infrastructure\Persistence\Core;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Router\Rota;

class RotaRepository extends Repository
{
    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function getTableName(): string
    {
        return 'rotas';
    }

    public function findByRoute($route)
    {
        return $this->findBy('nome', $route);
    }

    public function getModelClass(): string
    {
        return Rota::class;
    }
}
