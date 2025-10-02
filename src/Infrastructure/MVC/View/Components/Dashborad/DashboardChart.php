<?php

namespace Framework\Infrastructure\MVC\View\Components\Dashborad;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class DashboardChart implements IComponent
{
    const TIPO_BAR = 'bar';

    private $tipo;
    private $titulo;
    private $labels = [];
    private $route;

    public function __construct($tipo, $titulo)
    {
        $this->tipo = $tipo;
        $this->titulo = $titulo;
    }

    public function addLabel($nome, $titulo)
    {
        $this->labels[$nome] = $titulo;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function toArray(): array
    {
        return [
            'tipo' => $this->tipo,
            'titulo' => $this->titulo,
            'labels' => $this->labels,
            'route' => $this->route
        ];
    }
}
