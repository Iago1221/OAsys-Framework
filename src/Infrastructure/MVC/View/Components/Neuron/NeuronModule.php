<?php

namespace Framework\Infrastructure\MVC\View\Components\Neuron;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class NeuronModule implements IComponent
{
    protected $id;
    protected $label;
    protected $enable;
    protected $agentRoute;

    /** @var NeuronIntent[] */
    protected array $intents = [];
    protected $allowedOpenRoutes = [];

    public function __construct($id, $label, $enable, $agentRoute, $intents, $allowedOpenRoutes = [])
    {
        $this->id = $id;
        $this->label = $label;
        $this->enable = $enable;
        $this->agentRoute = $agentRoute;
        $this->intents = $intents;
        $this->allowedOpenRoutes = $allowedOpenRoutes;
    }

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
            'intents' => array_map(function (NeuronIntent $intent) {
                return $intent->toArray();
            }, $this->intents),
            'allowedOpenRoutes' => $this->allowedOpenRoutes
        ];
    }
}
