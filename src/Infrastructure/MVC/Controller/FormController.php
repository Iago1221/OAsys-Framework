<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;

abstract class FormController extends Controller
{
    public function show($bDisabled = true)
    {
        $oModel = $_GET['id'] ? $this->getMapper()->find([$this->getMapper()->getIdentifierAtributte() => $_GET['id']]) : null;
        if ($oModel) {
            $this->formBean($oModel);
        }

        $aData['sTitle'] = Main::getOrder()->getTitle();
        $aData['sRoute'] = Main::getOrder()->getRoute();
        $aData['bDisabled'] = $bDisabled;

        $this->beforeRender($oModel, $aData);
        $this->oView->render($aData);
    }

    protected function beforeRender($oModel, $aData) {}

    protected function formBean($oModel)
    {
        $aData = $this->getMapper()->getAtributtesData($oModel);
        $aComponents = $this->oView->getComponents();
        $aNewComponents = [];

        foreach ($aComponents as $oComponent) {
            if ($oComponent instanceof FormField) {
                $sField = $oComponent->getField();

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

            $oModel = $this->getMapper()->createFromAtributtes($this->getRequest());

            $this->beforeAdd($oModel);
            $oModel->setId($this->getMapper()->save($oModel));
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
            Main::getPdoStorage()->beginTransaction();
            $aData = $this->getRequest();
            $oModel =  $this->getMapper()->find([$this->getMapper()->getIdentifierAtributte() => $aData[$this->getMapper()->getIdentifierAtributte()]]);
            $oModel = $this->bean($oModel, $aData);

            $this->beforeEdit($oModel);
            $this->getMapper()->save($oModel);
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
            $oModel = $this->getMapper()->find([$this->getMapper()->getIdentifierAtributte() => $this->getRequest('iId')]);

            $this->beforeDelete($oModel);
            $this->getMapper()->remove($oModel);
            $this->afterDelete($oModel);

            Main::getPdoStorage()->commit();
        } catch (\Throwable $t) {
            Main::getPdoStorage()->rollback();
            throw $t;
        }
    }
}
