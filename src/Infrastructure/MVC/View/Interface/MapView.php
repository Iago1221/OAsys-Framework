<?php

namespace Framework\Infrastructure\MVC\View\Interface;

use Framework\Infrastructure\MVC\View\Components\Map\Map;

/**
 * View para o MapComponent (mapa com pontos e rotas).
 * O controller deve usar beforeRender() para chamar setPoints(), setRoutes(), etc.
 */
abstract class MapView extends View
{
    /** @var array */
    private $points = [];

    /** @var array */
    private $routes = [];

    /** @var array|null */
    private $routeOrder = null;

    /** @var array|null */
    private $center = null;

    /** @var int */
    private $zoom = 13;

    /** @var bool */
    private $bDisabled = false;

    /** @var string|null */
    private $width = null;

    protected function instanciaViewComponent()
    {
        $this->setViewComponent(new Map());
    }

    /**
     * @param array{id: string|int, lat: float, lng: float, label?: string, color?: string}[] $points
     */
    public function setPoints(array $points): self
    {
        $this->points = $points;
        return $this;
    }

    /** @return array */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param array{from: string|int, to: string|int, color?: string}[] $routes
     */
    public function setRoutes(array $routes): self
    {
        $this->routes = $routes;
        return $this;
    }

    /** @return array */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param (string|int)[]|null $order
     */
    public function setRouteOrder(?array $order): self
    {
        $this->routeOrder = $order;
        return $this;
    }

    /** @return array|null */
    public function getRouteOrder()
    {
        return $this->routeOrder;
    }

    /**
     * @param array{lat: float, lng: float}|null $center
     */
    public function setCenter(?array $center): self
    {
        $this->center = $center;
        return $this;
    }

    /** @return array|null */
    public function getCenter()
    {
        return $this->center;
    }

    public function setZoom(int $zoom): self
    {
        $this->zoom = $zoom;
        return $this;
    }

    public function getZoom(): int
    {
        return $this->zoom;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->bDisabled = $disabled;
        return $this;
    }

    public function setWidth(?string $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param array $aData ['bDisabled' => bool, ...]
     */
    public function render($aData = [])
    {
        /** @var Map $oMap */
        $oMap = $this->getViewComponent();
        $oMap->setPoints($this->getPoints());
        $oMap->setRoutes($this->getRoutes());
        $oMap->setRouteOrder($this->getRouteOrder());
        $oMap->setCenter($this->getCenter());
        $oMap->setZoom($this->getZoom());
        $oMap->setRoute($this->getRota());
        $oMap->setTitle($this->getTitulo());
        $oMap->setDisabled($aData['bDisabled'] ?? $this->bDisabled);

        if ($this->width !== null) {
            $oMap->setWidth($this->width);
        }

        if (isset($this->scriptFile)) {
            $oMap->setScriptFile($this->scriptFile);
        }

        echo json_encode($oMap->toArray());
    }
}
