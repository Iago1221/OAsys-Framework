<?php
namespace Framework\Interface\Infrastructure\Persistence\Sistema\Modulo;

use Framework\Infrastructure\DB\Persistence\Storage\Mapper\ReflectionMapper;
use Framework\Infrastructure\DB\Persistence\Storage\Mapper\Relationship;
use Framework\Interface\Domain\Modulo\Modulo;

class ModuloMapper extends ReflectionMapper
{
    protected function setRelationships()
    {
        // Relacionamento 1-N com Itens
        $this->addRelationship('aItens', new Relationship(
            Relationship::TYPE_ONE_TO_MANY,
            new ModuloItemMapper($this->getRepository()),
            'iModulo', // FK na tabela de itens
            'iId' // PK no módulo
        ));
    }

    public function getTable(): string
    {
        return 'modulos';
    }

    public function getColumns(): array
    {
        return [
            'id' => 'iId',
            'situacao' => 'iSituacao',
            'titulo' => 'sTitulo',
            'pacote' => 'sPacote'
        ];
    }

    public function getModelClass(): string
    {
        return Modulo::class;
    }
}