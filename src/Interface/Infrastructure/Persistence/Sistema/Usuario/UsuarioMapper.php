<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Usuario;

use Framework\Infrastructure\DB\Persistence\Storage\Mapper\ReflectionMapper;
use Framework\src\Sistema\Domain\Sistema\Usuario\Usuario;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class UsuarioMapper extends ReflectionMapper
{
    public function getTable()
    {
        return 'usuarios';
    }

    public function getColumns()
    {
        return [
            'id' => 'iId',
            'nome' => 'sNome',
            'senha' => 'sSenha',
            'email' => 'sEmail'
        ];
    }

    protected function setRelationships()
    {

    }

    public function getModelClass()
    {
        return Usuario::class;
    }
}