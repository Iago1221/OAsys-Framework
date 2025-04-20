<?php

namespace Framework\Infrastructure\DB\Persistence\Storage\Repository;


use Framework\Infrastructure\DB\Persistence\Storage\GenericStorage;
use Framework\Infrastructure\DB\Persistence\Storage\IStorage;

/**
 * Repositório Genérico para CRUD.
 *
 * @since 24/02/2025
 * @author Iago Oliveira <iago.oliveira@gmail.com>
 */
class GenericRepository
{
    /** @var IStorage */
    private $oStorage;

    public function __construct(GenericStorage $oStorage)
    {
        $this->oStorage = $oStorage;
    }

    public function get(string $sTable, array $aFilters = [], ?int $iLimit = null, ?int $iOffset = null, array $aOrderBy = [])
    {
        $this->oStorage->from($sTable);
        return $this->oStorage->get($aFilters, $iLimit, $iOffset, $aOrderBy);
    }

    public function find(string $sTable, array $aFilters = [], array $aOrderBy = [])
    {
        $this->oStorage->from($sTable);
        return $this->oStorage->find($aFilters, $aOrderBy);
    }

    public function add($sTable, $aData)
    {
        $this->oStorage->from($sTable);
        return $this->oStorage->add($aData);
    }

    public function update($sTable, $aFilters, $aData)
    {
        $this->oStorage->from($sTable);
        $this->oStorage->update($aFilters, $aData);
    }

    public function exists($sTable, $aFilters)
    {
        $this->oStorage->from($sTable);
        return $this->oStorage->exists($aFilters);
    }

    public function remove($sTable, $aFilters)
    {
        $this->oStorage->from($sTable);
        $this->oStorage->delete($aFilters);
    }
}
