<?php

namespace Framework\Infrastructure\MVC\View\Components\Dashborad;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class Dashboard implements IComponent
{
    private $name;
    private $titulo;
    private $metricas = [];
    private $charts = [];
    private $metricaRoute;

    public function __construct($name, $titulo)
    {
        $this->name = $name;
        $this->titulo = $titulo;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addMetrica(DashboardMetrica $metrica)
    {
        $this->metricas[] = $metrica;
    }

    public function addChart(DashBoardChart $chart)
    {
        $this->charts[] = $chart;
    }

    public function setMetricaRoute($route)
    {
        $this->metricaRoute = $route;
    }

    public function toArray(): array
    {
        return [
            'component' => 'DashboardComponent',
            'DashboardComponent' => [
                'titulo' => $this->titulo,
                'metricaRoute' => $this->metricaRoute,
                'metricas' => array_values(array_map(function ($metrica) {
                    return json_encode($metrica->toArray());
                }, $this->metricas)),
                'charts' => array_values(array_map(function ($chart) {
                    return json_encode($chart->toArray());
                }, $this->charts))
            ]
        ];
    }
}
