<?php

namespace Framework\Infrastructure\MVC\View\Components\Form;

use Framework\Infrastructure\MVC\View\Components\Fields\FormComponent;
use Framework\Infrastructure\MVC\View\Components\IComponent;

class Form implements IComponent
{
    private $aComponents;
    private $sLayout;
    private $bDisabled;
    private $sRoute;
    private $sTitle;
    private $isRelatorio;

    public function __construct(array $aComponents, string $sLayout, string $sRoute, string $sTitle, bool $bDisabled = false)
    {
        $this->aComponents = $aComponents;
        $this->sLayout = $sLayout;
        $this->bDisabled = $bDisabled;
        $this->sRoute = $sRoute;
        $this->sTitle = $sTitle;
        $this->isRelatorio = false;
    }

    public function setRelatorio($isRelatorio = true) {
        $this->isRelatorio = $isRelatorio;
    }

    public function toArray(): array
    {
        if ($this->bDisabled) {
            /** @var FormComponent $oComponent */
            foreach ($this->aComponents as $oComponent) {
                $oComponent->setDisabled();
            }
        }

        $aData = [
            'window' => [
                'title' => $this->sTitle,
                'route' => $this->sRoute
            ],
            'component' => 'FormComponent',
            'FormComponent' => [
                'components' => array_values(array_map(function ($oComponent) {
                    return json_encode($oComponent->toArray());
                }, $this->aComponents)),
                'layout' => $this->sLayout,
                'disabled' => $this->bDisabled,
                'route' => $this->sRoute,
                'isRelatorio' => $this->isRelatorio
            ]
        ];

        return $aData;
    }
}
