<?php

namespace Framework\Infrastructure\MVC\View\Interface;

use Framework\Infrastructure\MVC\View\Components\Form\Form;
use Framework\Infrastructure\MVC\View\Components\IComponent;

abstract class FormView extends View
{
    const FORM_LAYOUT_ONE_COLUMN = 'form-one-column';
    const FORM_LAYOUT_TWO_COLUMNS = 'form-two-columns';

    /** @var IComponent[] */
    private $aComponents = [];
    private $sFormLayout = self::FORM_LAYOUT_TWO_COLUMNS;
    private $isRelatorio = false;
    private $width;

    protected function instanciaViewComponent()
    {
        //$this->setComponent(new Form());
    }

    protected function addComponent(IComponent $oComponent)
    {
        $this->aComponents[$oComponent->getName()] = $oComponent;
        return $oComponent;
    }

    public function getComponent($name)
    {
        return $this->aComponents[$name];
    }

    protected function setFormLayout(string $sFormLayout)
    {
        $this->sFormLayout = $sFormLayout;
    }

    /** @return IComponent[] */
    public function getComponents()
    {
        return $this->aComponents;
    }

    /**
     * @param IComponent[] $aComponents
     * @return void
     */
    public function setComponents(array $aComponents)
    {
        $this->aComponents = $aComponents;
    }

    public function isRelatorio() {
        $this->isRelatorio = true;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function render($aData = [])
    {
        $oForm = new Form($this->getComponents(), $this->sFormLayout, $this->getRota(), $this->getTitulo(), $aData['bDisabled']);
        $oForm->setRelatorio($this->isRelatorio);

        if (isset($this->width)) {
            $oForm->setWidth($this->width);
        }

        if (isset($this->scriptFile)) {
            $oForm->setScriptFile($this->scriptFile);
        }

        echo json_encode($oForm->toArray());
    }
}
