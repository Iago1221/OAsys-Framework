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
    public function create($aData)
    {
        $oReflection = new \ReflectionClass($this->getModelClass());
        $oModel = $oReflection->newInstanceWithoutConstructor();
        foreach ($this->getColumns() as $sColumn => $sAtribute) {
            if ($oReflection->hasProperty($sAtribute)) {
                $oProperty = $oReflection->getProperty($sAtribute);
                $oProperty->setAccessible(true);

                if ($this->hasRelationship($sAtribute)) {
                    $oMapper = $this->getRelationship($sAtribute);
                    $oValue = $oMapper->find([$oMapper->getIdentifierAtributte() => $aData[$sColumn]]);
                    $oProperty->setValue($oModel, $oValue);
                    continue;
                }

                $oProperty->setValue($oModel, $aData[$sColumn]);
            }
        }

        return $oModel;
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
                    $oMapper = $this->getRelationship($sAtribute);
                    $oValue = $oMapper->createFromAtributtes($aData[$sAtribute]);
                    $oProperty->setValue($oModel, $oValue);
                    continue;
                }

                $oProperty->setValue($oModel, $aData[$sAtribute]);
            }
        }

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
                    $oMapper = $this->getRelationship($sAtribute);
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
                    $oMapper = $this->getRelationship($sAtribute);
                    $aData[$sAtribute] = $oMapper->getAtributtesData($aData[$sAtribute]);
                }
            }
        }

        return $aData;
    }

    /** @return string */
    abstract public function getModelClass();
}
