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

    protected function instanciaComponent()
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

    public function render($aData = [])
    {
        $oForm = new Form($this->getComponents(), $this->sFormLayout, $this->getRota(), $this->getTitulo(), $aData['bDisabled']);
        echo json_encode($oForm->toArray());
    }
}
