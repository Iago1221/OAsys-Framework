<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Modulo;

use Framework\Infrastructure\DB\Persistence\Storage\Mapper\ReflectionMapper;
use Framework\Interface\Domain\Modulo\Modulo;

class ModuloMapper extends ReflectionMapper
{
    protected function setRelationships()
    {
    }

    public function getTable()
    {
        return 'modulos';
    }

    public function getColumns()
    {
        return [
            'id' => 'iId',
            'situacao' => 'iSituacao',
            'titulo' => 'sTitulo',
            'pacote' => 'sPacote'
        ];
    }

    public function getModelClass()
    {
        return Modulo::class;
    }
}