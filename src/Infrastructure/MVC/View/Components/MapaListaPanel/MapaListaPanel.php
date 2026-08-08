<?php

namespace Framework\Infrastructure\MVC\View\Components\MapaListaPanel;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class MapaListaPanel implements IComponent
{
    private array $points = [];
    private ?array $center = null;
    private int $zoom = 6;
    private bool $showList = true;
    private ?MapaListaPanelList $list = null;
    private array $routes = [];

    public function getName(): string
    {
        return 'MapaListaPanelComponent';
    }

    public function setPoints(array $points): self
    {
        $this->points = $points;
        return $this;
    }

    public function setCenter(?array $center): self
    {
        $this->center = $center;
        return $this;
    }

    public function setZoom(int $zoom): self
    {
        $this->zoom = $zoom;
        return $this;
    }

    public function setShowList(bool $showList): self
    {
        $this->showList = $showList;
        return $this;
    }

    public function setList(?MapaListaPanelList $list): self
    {
        $this->list = $list;
        return $this;
    }

    public function setRoutes(array $routes): self
    {
        $this->routes = $routes;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'component'              => 'MapaListaPanelComponent',
            'MapaListaPanelComponent' => [
                'points'   => $this->points,
                'center'   => $this->center,
                'zoom'     => $this->zoom,
                'showList' => $this->showList,
                'list'     => $this->list?->toArray(),
                'routes'   => $this->routes,
            ],
        ];
    }
}
