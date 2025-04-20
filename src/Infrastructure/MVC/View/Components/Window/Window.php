<?php

namespace Framework\Infrastructure\MVC\View\Components\Window;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class Window implements IComponent
{
    private $sTitle;
    private $sRoute;
    private $aComponents;

    public function addComponent(IComponent $oComponent)
    {
        $this->aComponents[] = $oComponent;
    }

    public function setTitle(string $sTitle)
    {
        $this->sTitle = $sTitle;
    }

    public function getTitle()
    {
        return $this->sTitle;
    }

    public function setRoute(string $sRoute)
    {
        $this->sRoute = $sRoute;
    }

    public function getRoute()
    {
        return $this->sRoute;
    }

    public function toArray() : array
    {
        return [
            'window' => [
                'title' => $this->sTitle,
                'route' => $this->sRoute
            ],
            'component' => 'WindowComponent',
            'WindowComponent' => [
                'components' => array_map(function ($oComponent) {
                    return $oComponent->toArray();
                }, $this->aComponents)
            ]
        ];
    }
}
