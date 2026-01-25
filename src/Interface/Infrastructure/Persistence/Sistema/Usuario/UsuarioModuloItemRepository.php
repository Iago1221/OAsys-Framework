<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Usuario;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Usuario\UsuarioModuloItem;

class UsuarioModuloItemRepository extends Repository
{
    protected function getModelClass(): string
    {
        return UsuarioModuloItem::class;
    }

    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function getTableName(): string
    {
        return 'usuario_modulo_itens';
    }
}
