<?php

namespace Framework\Infrastructure\MVC\View\Components\Neuron;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class NeuronWorkspace implements IComponent
{
    protected $modules = [];

    public function addModule(NeuronModule $module): void {
        $this->modules[] = $module;
    }

    public function toArray(): array
    {
        return [
            'window' => [
                'title' => 'Oasys Neuron',
                'route' => 'sys_oasys_neuron',
                'width' => 'min(1180px, 96vw)',
                'fullscreen' => true,
            ],
            'component' => 'NeuronComponent',
            'NeuronComponent' => [
                'modules' => array_map(function (NeuronModule $module) {
                    return $module->toArray();
                }, $this->modules),
            ],
        ];
    }
}