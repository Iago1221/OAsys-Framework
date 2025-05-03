<?php

namespace Framework\Infrastructure\DB\Persistence\Storage\Mapper;

use Framework\Infrastructure\DB\Persistence\Storage\Mapper\GenericMapper;

/**
 * DataMapper que trata os dados do modelo e persitência por reflexão.
 *
 * @since 24/02/2025
 * @author Iago Oliveira <iago.oliveira@gmail.com>
 */
abstract class ReflectionMapper extends GenericMapper
{
    /** @inheritDoc */
    public function create($aData): object
    {
        $reflection = new \ReflectionClass($this->getModelClass());
        $model = $reflection->newInstanceWithoutConstructor();

        foreach ($this->getColumns() as $column => $attribute) {
            if ($reflection->hasProperty($attribute)) {
                $property = $reflection->getProperty($attribute);
                $property->setAccessible(true);

                if ($this->hasRelationship($attribute)) {
                    $property->setValue($model, null); // Carregamento lazy
                    continue;
                }

                $property->setValue($model, $aData[$column] ?? null);
            }
        }

        $this->loadRelationships($model);
        return $model;
    }

    public function loadRelationships(object $model): void
    {
        foreach ($this->relationships as $attribute => $relationship) {
            $this->loadRelationship($model, $attribute, $relationship);
        }
    }

    public function createFromAtributtes($aData)
    {
        $oReflection = new \ReflectionClass($this->getModelClass());
        $oModel = $oReflection->newInstanceWithoutConstructor();
        foreach ($this->getColumns() as $sColumn => $sAtribute) {
            if ($oReflection->hasProperty($sAtribute) && $aData[$sAtribute]) {
                $oProperty = $oReflection->getProperty($sAtribute);
                $oProperty->setAccessible(true);

                if ($this->hasRelationship($sAtribute)) {
                    $oMapper = $this->getRelationship($sAtribute)->getMapper();
                    $oValue = $oMapper->createFromAtributtes($aData[$sAtribute]);
                    $oProperty->setValue($oModel, $oValue);
                    continue;
                }

                $oProperty->setValue($oModel, $aData[$sAtribute]);
            }
        }

        $this->loadRelationships($oModel);
        return $oModel;
    }

    /** @inheritDoc */
    public function getData($oModel)
    {
        if (!($oModel instanceof ($this->getModelClass()))) {
            return null;
        }

        $oReflection = new \ReflectionClass($this->getModelClass());
        $aData = [];
        foreach ($this->getColumns() as $sColumn => $sAtribute) {
            if ($oReflection->hasProperty($sAtribute)) {
                $oProperty = $oReflection->getProperty($sAtribute);
                $oProperty->setAccessible(true);
                $aData[$sColumn] = $oProperty->getValue($oModel);

                if ($this->hasRelationship($sAtribute)) {
                    $oMapper = $this->getRelationship($sAtribute)->getMapper();
                    $aData[$sColumn] = $oMapper->getData($aData[$sColumn])[$oMapper->getIdentifierColumn()];
                }
            }
        }

        return $aData;
    }

    public function getAtributtesData($oModel)
    {
        if (!($oModel instanceof ($this->getModelClass()))) {
            return null;
        }

        $oReflection = new \ReflectionClass($this->getModelClass());
        $aData = [];
        foreach ($this->getColumns() as $sColumn => $sAtribute) {
            if ($oReflection->hasProperty($sAtribute)) {
                $oProperty = $oReflection->getProperty($sAtribute);
                $oProperty->setAccessible(true);
                $aData[$sAtribute] = $oProperty->getValue($oModel);

                if ($this->hasRelationship($sAtribute)) {
                    $oMapper = $this->getRelationship($sAtribute)->getMapper();
                    $aData[$sAtribute] = $oMapper->getAtributtesData($aData[$sAtribute]);
                }
            }
        }

        return $aData;
    }

    /** @return string */
    abstract public function getModelClass();
}
