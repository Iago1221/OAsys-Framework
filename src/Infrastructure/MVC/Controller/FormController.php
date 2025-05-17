<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;

abstract class FormController extends Controller
{
    public function show($bDisabled = true)
    {
        $oModel = $_GET['id'] ?
            $this->getRepository()->findBy('id', $_GET['id']) :
            null;

        if ($oModel) {
            $this->formBean($oModel);
        }

        $this->getView()->setTitulo(Main::getOrder()->getTitle());
        $this->getView()->setRota(Main::getOrder()->getRoute());

        $aData['bDisabled'] = $bDisabled;

        $this->beforeRender($oModel, $aData);
        $this->getView()->render($aData);
    }

    protected function beforeRender($oModel, &$aData) {}

    protected function formBean($oModel)
    {
        $aData = $this->mapModelToArray($oModel);
        $aComponents = $this->getView()->getComponents();
        $aNewComponents = [];

        foreach ($aComponents as $oComponent) {
            if ($oComponent instanceof FormField) {
                $sField = $oComponent->getName();

                if (strpos($sField, '/') !== false) {
                    $parts = explode('/', $sField);
                    $sParentKey = $parts[0];
                    $sChildKey = $parts[1];

                    if (isset($aData[$sParentKey]) && is_array($aData[$sParentKey])) {
                        $aNested = $aData[$sParentKey];
                        if ($aNested[$sChildKey]) {
                            $oComponent->setValue($aNested[$sChildKey]);
                        }
                    }
                } else {
                    if (isset($aData[$sField])) {
                        $oComponent->setValue($aData[$sField]);
                    }
                }

                $aNewComponents[] = $oComponent;
                continue;
            }

            $oComponent->bean($aData);
            $aNewComponents[] = $oComponent;
        }

        $this->oView->setComponents($aNewComponents);
    }

    /** @return void|null */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->show(false);
            return;
        }

        try {
            Main::getPdoStorage()->beginTransaction();
            $this->getRepository()->setControlaTransacao(false);
            $oModel = $this->getRepository()->mapToModel($this->getRequest());

            $this->beforeAdd($oModel);
            $this->getRepository()->saveWithRelations($oModel);
            $this->afterAdd($oModel);

            Main::getPdoStorage()->commit();
        } catch (\Throwable $t) {
            Main::getPdoStorage()->rollback();
            throw $t;
        }
    }

    protected function beforeAdd($oModel) {}
    protected function afterAdd($oModel) {}
    protected function beforeEdit($oModel) {}
    protected function afterEdit($oModel) {}
    protected function beforeDelete($oModel) {}
    protected function afterDelete($oModel) {}

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->show(false);
            return;
        }

        try {
            $aData = $this->getRequest();
            Main::getPdoStorage()->beginTransaction();

            $this->getRepository()->setControlaTransacao(false);
            $oModel = $this->getRepository()->findBy('id', $aData['id']);
            //$oModel = $this->bean($oModel, $aData);

            $this->beforeEdit($oModel);
            $this->getRepository()->saveWithRelations($oModel);
            $this->afterEdit($oModel);

            Main::getPdoStorage()->commit();
        } catch (\Throwable $t) {
            Main::getPdoStorage()->rollback();
            throw $t;
        }
    }

    protected function bean($oModel, $aData)
    {
        $oReflection = new \ReflectionClass($oModel);

        foreach ($aData as $aField => $aValue) {
            if ($oReflection->hasProperty($aField)) {
                $oReflectionProperty = $oReflection->getProperty($aField);
                $oReflectionProperty->setAccessible(true);

                if (is_array($aValue)) {
                    $oRelationshipMapper = $this->getMapper()->getRelationship($aField);
                    $oReflectionProperty->setValue($oModel, null);

                    if ($aValue[$oRelationshipMapper->getIdentifierAtributte()]) {
                        $oValue = $oRelationshipMapper->find([$oRelationshipMapper->getIdentifierAtributte() => $aValue[$oRelationshipMapper->getIdentifierAtributte()]]);
                        $oReflectionProperty->setValue($oModel, $oValue);
                    }

                    continue;
                }

                $oReflectionProperty->setValue($oModel, $aValue);
            }
        }

        return $oModel;
    }

    public function delete()
    {
        try {
            Main::getPdoStorage()->beginTransaction();
            $oModel = $this->getRepository()->findBy('id', $this->getRequest()->getParam('id'));

            $this->beforeDelete($oModel);
            $this->getRepository()->remove($oModel);
            $this->afterDelete($oModel);

            Main::getPdoStorage()->commit();
        } catch (\Throwable $t) {
            Main::getPdoStorage()->rollback();
            throw $t;
        }
    }
}
