<?php

namespace Framework\Infrastructure\DB\Persistence\Storage\Mapper;

use Framework\Infrastructure\DB\Persistence\Storage\Repository\GenericRepository;

/**
 * Abstração genérica para DataMapper (Mapeamento de objeto de domínio para objeto de persistência).
 *
 * @since 24/02/2025
 * @author Iago Oliveira <iago.oliveira@gmail.com>
 */
abstract class GenericMapper implements IMapper
{
    /** @var Relationship[] */
    protected array $relationships = [];

    /** @var GenericRepository */
    protected $oRepository;

    public function __construct(GenericRepository $oRepository)
    {
        $this->oRepository = $oRepository;
        $this->setRelationships();
    }

    /** @return GenericRepository */
    public function getRepository()
    {
        return $this->oRepository;
    }

    /**
     * Utilizar para definir os relacionamentos dos atributos da classe com outros Mappers.
     * @return void
     */
    abstract protected function setRelationships();


    /** @inheritDoc */
    public function getColumn($sAtributte)
    {
        foreach ($this->getColumns() as $sColumn => $sClassAtributte) {
            if ($sClassAtributte == $sAtributte) {
                return $sColumn;
            }
        }

        throw new \UnexpectedValueException('Atributo não encontrado');
    }

    /** @inheritDoc */
    public function getAtributte($sColumn)
    {
        if ($this->getColumns()[$sColumn]) {
            return $this->getColumns()[$sColumn];
        }

        throw new \UnexpectedValueException('Coluna não encontrada');
    }

    /** @inheritDoc */
    public function getIdentifierColumn()
    {
        return array_keys($this->getColumns())[0];
    }

    /** @inheritDoc */
    public function getIdentifierAtributte()
    {
        return array_values($this->getColumns())[0];
    }

    protected function addRelationship(string $attribute, Relationship $relationship): void
    {
        $this->relationships[$attribute] = $relationship;
    }

    public function getRelationship(string $attribute): ? Relationship
    {
        return $this->relationships[$attribute] ?? null;
    }

    public function hasRelationship(string $attribute): bool
    {
        return isset($this->relationships[$attribute]);
    }

    protected function loadRelationship(object $model, string $attribute, Relationship $relationship): void
    {
        $reflection = new \ReflectionClass($model);
        $property = $reflection->getProperty($attribute);
        $property->setAccessible(true);

        switch ($relationship->getType()) {
            case Relationship::TYPE_ONE_TO_ONE:
                $value = $this->loadOneToOne($model, $relationship);
                break;
            case Relationship::TYPE_ONE_TO_MANY:
                $value = $this->loadOneToMany($model, $relationship);
                break;
            case Relationship::TYPE_MANY_TO_MANY:
                $value = $this->loadManyToMany($model, $relationship);
                break;
            default:
                throw new \InvalidArgumentException("Tipo de relacionamento inválido");
        }

        $property->setValue($model, $value);
    }

    protected function loadOneToOne(object $model, Relationship $relationship): ?object
    {
        $reflection = new \ReflectionClass($model);
        $localKey = $relationship->getLocalKey();
        $localKeyProperty = $reflection->getProperty($localKey);
        $localKeyProperty->setAccessible(true);
        $localValue = $localKeyProperty->getValue($model);

        if (!$localValue) {
            return null;
        }

        return $relationship->getMapper()->find([
            $relationship->getForeignKey() => $localValue
        ]);
    }

    protected function loadOneToMany(object $model, Relationship $relationship): array
    {
        $reflection = new \ReflectionClass($model);
        $localKey = $relationship->getLocalKey();
        $localKeyProperty = $reflection->getProperty($localKey);
        $localKeyProperty->setAccessible(true);
        $localValue = $localKeyProperty->getValue($model);

        if (!$localValue) {
            return [];
        }

        return $relationship->getMapper()->get([
            $relationship->getForeignKey() => $localValue
        ]);
    }

    protected function loadManyToMany(object $model, Relationship $relationship): array
    {
        $reflection = new \ReflectionClass($model);
        $localKey = $relationship->getLocalKey();
        $localKeyProperty = $reflection->getProperty($localKey);
        $localKeyProperty->setAccessible(true);
        $localValue = $localKeyProperty->getValue($model);

        if (!$localValue) {
            return [];
        }

        // Busca os IDs relacionados na tabela pivot
        $storage = $relationship->getMapper()->getRepository()->getStorage();
        $storage->from($relationship->getPivotTable());
        $relatedIds = $storage->get([
            $relationship->getPivotLocalKey() => $localValue
        ]);

        if (empty($relatedIds)) {
            return [];
        }

        return $relationship->getMapper()->get([
            $relationship->getMapper()->getIdentifierColumn() => array_column($relatedIds, $relationship->getPivotForeignKey())
        ]);
    }

    /**
     * Busca todos os registros que contenha os filtros passados por parêmtros.
     * @param array $aFilters
     * @param int|null $iLimit
     * @param int|null $iOffset
     * @param array $aOrderBy
     * @return array
     */
    public function get(array $aFilters = [], ?int $iLimit = null, ?int $iOffset = null, array $aOrderBy = [])
    {
        $aBdFilters = [];
        foreach ($aFilters as $sAtributte => $xValue) {
            $aBdFilters[$this->getColumn($sAtributte)] = $xValue;
        }

        $aBdOrderBy = [];
        foreach ($aOrderBy as $sAtributte => $xValue) {
            $aBdOrderBy[$this->getColumn($sAtributte)] = $xValue;
        }

        $aRows = $this->getRepository()->get($this->getTable(), $aBdFilters, $iLimit, $iOffset, $aBdOrderBy);
        $aResult = [];
        foreach ($aRows as $aRow) {
            $aResult[] = $this->create($aRow);
        }

        return $aResult;
    }

    /**
     * Busca o primeiro registro igual ao filtro passado por parâmetro.
     * @param array $aFilters
     * @param array $aOrderBy
     * @return object|null
     */
    public function find(array $aFilters, array $aOrderBy = [])
    {
        $aBdFilters = [];
        foreach ($aFilters as $sAtributte => $xValue) {
            $aBdFilters[$this->getColumn($sAtributte)] = $xValue;
        }

        $aBdOrderBy = [];
        foreach ($aOrderBy as $sAtributte => $xValue) {
            $aBdOrderBy[$this->getColumn($sAtributte)] = $xValue;
        }

        $aRow = $this->getRepository()->find($this->getTable(), $aBdFilters, $aBdOrderBy);

        return $aRow ? $this->create($aRow) : null;
    }

    /**
     * Persiste o modelo passado por parâmetro.
     * @param object $oModel
     * @return void
     */
    public function save($oModel)
    {
        $aData = $this->getData($oModel);
        $xId = $aData[$this->getIdentifierColumn()];

        if ($xId && $this->getRepository()->exists($this->getTable(), [$this->getIdentifierColumn() => $xId])) {
            $this->getRepository()->update($this->getTable(), [$this->getIdentifierColumn() => $xId], $aData);
            return;
        }

        return $this->getRepository()->add($this->getTable(), $aData);
    }

    /**
     * Remove o modelo passado por parâmetro.
     * @param object $oModel
     * @return void
     */
    public function remove($oModel)
    {
        $aData = $this->getData($oModel);
        $xId = $aData[$this->getIdentifierColumn()];
        $this->getRepository()->remove($this->getTable(), [$this->getIdentifierColumn() => $xId]);
    }

    /**
     * Verifica se existe registros de acordo com os filtros passados por parâmetro.
     * @param array $aFilters
     * @return bool
     */
    public function exists(array $aFilters)
    {
        $aBdFilters = [];
        foreach ($aFilters as $sAtributte => $xValue) {
            $aBdFilters[$this->getColumn($sAtributte)] = $xValue;
        }

        return $this->getRepository()->exists($this->getTable(), $aBdFilters);
    }
}
