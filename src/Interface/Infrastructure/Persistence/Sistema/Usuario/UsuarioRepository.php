<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Usuario;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Usuario\Usuario;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class UsuarioRepository extends Repository
{
    protected function getModelClass(): string
    {
        return Usuario::class;
    }

    protected function getSchema(): ?string
    {
        return null;
    }

    protected function getTableName(): string
    {
        return 'usuarios';
    }
}