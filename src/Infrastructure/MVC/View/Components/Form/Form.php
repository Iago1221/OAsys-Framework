<?php

namespace Framework\Infrastructure\MVC\View\Components\Form;

use Framework\Infrastructure\MVC\View\Components\Fields\FormComponent;
use Framework\Infrastructure\MVC\View\Components\IComponent;

class Form implements IComponent
{
    private $aComponents;
    private $sLayout;
    private $bDisabled;
    private $criaBotaoSubmit;
    private $sRoute;
    private $sTitle;
    private $isRelatorio;
    private $width;
    private $scriptFile = null;
    private $buttons;

    public function __construct(array $aComponents = [], string $sLayout = null, string $sRoute = null, string $sTitle = null, bool $bDisabled = false, bool $criaBotaoSubmit = true)
    {
        $this->aComponents = $aComponents;
        $this->sLayout = $sLayout;
        $this->bDisabled = $bDisabled;
        $this->criaBotaoSubmit = $criaBotaoSubmit;
        $this->sRoute = $sRoute;
        $this->sTitle = $sTitle;
        $this->isRelatorio = false;
        $this->width = 'auto';
        $this->buttons = [];
    }

    public function setComponents(array $aComponents)
    {
        $this->aComponents = $aComponents;
    }

    public function setLayout(string $sLayout)
    {
        $this->sLayout = $sLayout;
    }

    public function setRoute($route)
    {
        $this->sRoute = $route;
    }

    public function setTitle(string $title)
    {
        $this->sTitle = $title;
    }

    public function setDisabled(bool $disabled)
    {
        $this->bDisabled = $disabled;
    }

    public function setCriaBotaoSubmit(bool $criaBotaoSubmit)
    {
        $this->criaBotaoSubmit = $criaBotaoSubmit;
    }

    public function setRelatorio($isRelatorio = true)
    {
        $this->isRelatorio = $isRelatorio;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }


    /* @param string $scriptFile
     */
    public function setScriptFile(string $scriptFile): void
    {
        $this->scriptFile = $scriptFile;
    }

    public function addButton(Button $button)
    {
        $this->buttons[] = $button;
    }

    public function toArray(): array
    {
        if ($this->bDisabled) {
            /** @var IComponent $oComponent */
            foreach ($this->aComponents as $oComponent) {
                if (method_exists($oComponent, 'setDisabled')) {
                    $oComponent->setDisabled();
                }
            }
        }

        $aData = [
            'window' => [
                'title' => $this->sTitle,
                'route' => $this->sRoute,
                'width' => $this->width,
            ],
            'scriptFile' => $this->scriptFile,
            'component' => 'FormComponent',
            'FormComponent' => [
                'components' => array_values(array_map(function ($oComponent) {
                    return json_encode($oComponent->toArray());
                }, $this->aComponents)),
                'layout' => $this->sLayout,
                'disabled' => $this->bDisabled,
                'criaBotaoSubmit' => $this->criaBotaoSubmit,
                'route' => $this->sRoute,
                'isRelatorio' => $this->isRelatorio,
                'width' => $this->width,
                'buttons' => array_values(array_map(function ($button) {
                    return json_encode($button->toArray());
                }, $this->buttons))
            ]
        ];

        return $aData;
    }
}
