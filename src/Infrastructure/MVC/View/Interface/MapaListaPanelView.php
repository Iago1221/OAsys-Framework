<?php

namespace Framework\Infrastructure\MVC\View\Interface;

use Framework\Infrastructure\MVC\View\Components\MapaListaPanel\MapaListaPanel;
use Framework\Infrastructure\MVC\View\Components\MapaListaPanel\MapaListaPanelColumn;
use Framework\Infrastructure\MVC\View\Components\MapaListaPanel\MapaListaPanelFilter;
use Framework\Infrastructure\MVC\View\Components\MapaListaPanel\MapaListaPanelList;

abstract class MapaListaPanelView extends View
{
    protected function instanciaViewComponent(): void
    {
        $this->setViewComponent(new MapaListaPanel());
    }

    protected function getPanel(): MapaListaPanel
    {
        return $this->getViewComponent();
    }

    protected function makeColumn(string $name, string $label, string $type, array $options = []): MapaListaPanelColumn
    {
        return new MapaListaPanelColumn($name, $label, $type, $options);
    }

    protected function makeFilter(string $name, string $label, string $type, array $options = [], mixed $value = null, bool $multiple = false): MapaListaPanelFilter
    {
        return new MapaListaPanelFilter($name, $label, $type, $options, $value, $multiple);
    }

    protected function makeList(string $title): MapaListaPanelList
    {
        return new MapaListaPanelList($title);
    }

    public function render($aData = []): void
    {
        $window = [
            'window' => [
                'title' => $this->getTitulo(),
                'route' => $this->getRota(),
            ],
        ];

        $component = array_merge($window, $this->getViewComponent()->toArray());
        echo json_encode($component);
    }
}
