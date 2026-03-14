<?php

namespace Framework\Infrastructure\MVC\View\Components\Map;

use Framework\Infrastructure\MVC\View\Components\IComponent;

/**
 * Componente de mapa (backend do MapComponent.js).
 * Gera o array esperado pelo frontend: points, routes, routeOrder, center, zoom, disabled.
 */
class Map implements IComponent
{
    /** @var array{id: string|int, lat: float, lng: float, label?: string, color?: string}[] */
    private $points = [];

    /** @var array{from: string|int, to: string|int, color?: string}[] */
    private $routes = [];

    /** @var (string|int)[]|null Ordem dos ids para uma única polilinha */
    private $routeOrder = null;

    /** @var array{lat: float, lng: float}|null */
    private $center = null;

    private $zoom = 13;
    private $bDisabled = false;
    private $sRoute = null;
    private $sTitle = null;
    private $width = '100%';
    private $scriptFile = null;

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

    /** @return (string|int)[]|null */
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

    /** @return array{lat: float, lng: float}|null */
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

    public function setRoute(?string $route): self
    {
        $this->sRoute = $route;
        return $this;
    }

    public function setTitle(?string $title): self
    {
        $this->sTitle = $title;
        return $this;
    }

    public function setWidth(string $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function setScriptFile(?string $scriptFile): self
    {
        $this->scriptFile = $scriptFile;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'window' => [
                'title' => $this->sTitle,
                'route' => $this->sRoute,
                'width' => $this->width,
            ],
            'scriptFile' => $this->scriptFile,
            'component' => 'MapComponent',
            'MapComponent' => [
                'points' => $this->points,
                'routes' => $this->routes,
                'routeOrder' => $this->routeOrder,
                'center' => $this->center,
                'zoom' => $this->zoom,
                'disabled' => $this->bDisabled,
            ],
        ];
    }
}
