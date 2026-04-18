<?php

namespace Framework\Infrastructure\MVC\View\Components\Neuron;

use Framework\Infrastructure\MVC\View\Components\IComponent;
use Framework\Interface\Application\Neuron\NeuronService;

class NeuronIntent implements IComponent
{
    protected string $intent;
    protected string $label;
    protected ?NeuronIntentForm $form;
    protected NeuronService $service;

    public function __construct(string $intent, string $label, NeuronService $service, ?NeuronIntentForm $form = null)
    {
        $this->intent = $intent;
        $this->label = $label;
        $this->service = $service;
        $this->form = $form;
    }

    public function toArray(): array
    {
        return [
            'intent' => $this->intent,
            'label' => $this->label,
            'form' => $this->form ? $this->form->toArray() : null
        ];
    }
}
