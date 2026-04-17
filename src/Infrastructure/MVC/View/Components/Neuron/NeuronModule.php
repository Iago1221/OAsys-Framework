<?php

namespace Framework\Infrastructure\MVC\View\Components\Neuron;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class NeuronModule implements IComponent
{
    protected $id;
    protected $label;
    protected $enable;
    protected $agentRoute;
    protected $intentsRoute;
    protected $allowedOpenRoutes = [];

    public function addAllowedOpenRoute(string $route, string $label) {
        $this->allowedOpenRoutes[] = ['route' => $route, 'label' => $label];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'enable' => $this->enable,
            'agentRoute' => $this->agentRoute,
            'intentsRoute' => $this->intentsRoute,
            'allowedOpenRoutes' => $this->allowedOpenRoutes
        ];
    }
}