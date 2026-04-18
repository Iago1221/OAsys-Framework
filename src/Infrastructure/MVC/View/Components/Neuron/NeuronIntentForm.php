<?php

namespace Framework\Infrastructure\MVC\View\Components\Neuron;

use Framework\Infrastructure\MVC\View\Components\IComponent;

abstract class NeuronIntentForm implements IComponent
{
    /** @var IComponent[] */
    protected array $components = [];

    public function __construct()
    {
        $this->create();
    }

    public function addComponent(IComponent $component)
    {
        $this->components[] = $component;
    }

    abstract protected function create(): void;

    public function toArray(): array
    {
        return [
            'component' => 'NeuronIntentFormComponent',
            'NeuronIntentFormComponent' => [
                'components' => array_map(function (IComponent $component) {
                    return $component->toArray();
                }, $this->components),
            ],
        ];
    }
}
