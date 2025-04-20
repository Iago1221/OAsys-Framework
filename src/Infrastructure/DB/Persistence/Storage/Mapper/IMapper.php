<?php

namespace Framework\Infrastructure\DB\Persistence\Storage\Mapper;

/**
 * Interface padrão para DataMapper (Mapeamento de objeto de domínio para objeto de persistência).
 *
 * @since 24/02/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
interface IMapper
{
    /**
     * Retorna a tabela onde o mapper irá persistir.
     * @return string
     */
    public function getTable();

    /**
     * Retorna a coluna da tabela que identifica o registro unicamente.
     * @return string
     */
    public function getIdentifierColumn();

    /**
     * Retorna o atributo do modelo que identifica o registro unicamente.
     * @return string
     */
    public function getIdentifierAtributte();

    /**
     * Retorna a relação entre as colunas da tabela e os atributos do modelo.
     * @return array
     */
    public function getColumns();

    /**
     * Retorna a coluna da tabela correspondente ao atributo do modelo passado por parâmetro.
     * @param string $sAtributte
     * @return string
     */
    public function getColumn($sAtributte);

    /**
     * Retorna o atributo do modelo correspondente a coluna da tabela passada por parâmetro.
     * @param string $sColumn
     * @return string
     */
    public function getAtributte($sColumn);

    /**
     * Retorna a instância do modelo inicializada atráves dos parâmetros de persistência.
     * @param array $aData
     * @return object
     */
    public function create($aData);

    /**
     * Retorna os parâmetros de persistência de um modelo passado por parâmetro.
     * @param object $oModel
     * @return array
     */
    public function getData($oModel);
}
