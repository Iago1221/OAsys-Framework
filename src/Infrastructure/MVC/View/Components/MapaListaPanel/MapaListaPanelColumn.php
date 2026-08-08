<?php

namespace Framework\Infrastructure\MVC\View\Components\MapaListaPanel;

class MapaListaPanelColumn
{
    public function __construct(
        private string $name,
        private string $label,
        private string $type,
        private array $options = []
    ) {}

    public function toArray(): array
    {
        return [
            'name'    => $this->name,
            'label'   => $this->label,
            'type'    => $this->type,
            'options' => $this->options,
        ];
    }
}
