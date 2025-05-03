<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Modulo;

use Framework\Infrastructure\DB\Persistence\Storage\Mapper\ReflectionMapper;
use Framework\Infrastructure\DB\Persistence\Storage\Mapper\Relationship;
use Framework\Infrastructure\DB\Persistence\Storage\Repository\GenericRepository;
use Framework\Interface\Domain\Modulo\ModuloItem;
use Framework\Interface\Infrastructure\Persistence\Core\RotaMapper;

class ModuloItemMapper extends ReflectionMapper
{
    protected function setRelationships()
    {
    }

    public function getTable()
    {
        return 'modulo_itens';
    }

    public function getColumns()
    {
        return [
            'id' => 'iId',
            'titulo' => 'sTitulo',
            'situacao' => 'iSituacao',
            'rota' => 'oRota',
            'modulo' => 'iModulo'
        ];
    }

    protected function afterCreate($oModel, $aData) {
        $iRota = $aData['rota'] ?: $aData['oRota'];

        if ($iRota) {
            $oRota = (new RotaMapper($this->getRepository()))->find(['iId' => $iRota ]);
            $oModel->setRota($oRota);
        }
    }

    public function getModelClass()
    {
        return ModuloItem::class;
    }
}