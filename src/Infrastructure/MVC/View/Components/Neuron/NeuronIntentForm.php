<?php

namespace Framework\Infrastructure\MVC\View\Components\Neuron;

use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Infrastructure\MVC\View\Components\IComponent;

abstract class NeuronIntentForm implements IComponent
{
    /** @var FormField[] */
    protected array $fields = [];

    public function __construct()
    {
        $this->create();
    }

    public function addField(FormField $field)
    {
        $this->fields[] = $field;
    }

    abstract protected function create(): void;

    public function toArray(): array
    {
        return [
            'component' => 'NeuronIntentFormComponent',
            'NeuronIntentFormComponent' => [
                'fields' => array_map(function (FormField $field) {
                    return $field->toArray();
                }, $this->fields),
            ],
        ];
    }
}
