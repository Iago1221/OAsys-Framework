<?php

namespace Framework\Infrastructure\MVC\View\Components\MapaListaPanel;

class MapaListaPanelFilter
{
    public function __construct(
        private string $name,
        private string $label,
        private string $type,
        private array $options = [],
        private mixed $value = null,
        private bool $multiple = false
    ) {}

    public function toArray(): array
    {
        return [
            'name'     => $this->name,
            'label'    => $this->label,
            'type'     => $this->type,
            'options'  => $this->options,
            'value'    => $this->value,
            'multiple' => $this->multiple,
        ];
    }
}
