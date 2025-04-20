<?php

namespace Framework\Interface\Infrastructure\Persistence\Core;

use Framework\Infrastructure\DB\Persistence\Storage\Mapper\ReflectionMapper;
use Framework\Interface\Domain\Router\Rota;

class RotaMapper extends ReflectionMapper
{
    protected function setRelationships()
    {
    }

    public function getTable()
    {
        return 'rotas';
    }

    public function getColumns()
    {
        return [
            'id' => 'iId',
            'nome' => 'sNome',
            'caminho' => 'sCaminho',
            'metodo'  => 'sMetodo',
            'titulo' => 'sTitulo'
        ];
    }

    public function findByRoute($sRoute)
    {
        return $this->find(['sNome' => $sRoute]);
    }

    public function getModelClass()
    {
        return Rota::class;
    }
}