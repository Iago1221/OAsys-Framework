<?php

namespace Framework\Infrastructure\MVC\View\Components\ConciliacaoPanel;

class ConciliacaoPanelFilter
{
    public function __construct(
        private string $name,
        private string $label,
        private string $type,
        private array $options = [],
        private mixed $value = null
    ) {}

    public function toArray(): array
    {
        return [
            'name'    => $this->name,
            'label'   => $this->label,
            'type'    => $this->type,
            'options' => $this->options,
            'value'   => $this->value,
        ];
    }
}
