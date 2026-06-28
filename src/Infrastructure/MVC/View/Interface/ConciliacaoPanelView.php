<?php

namespace Framework\Infrastructure\MVC\View\Interface;

use Framework\Infrastructure\MVC\View\Components\ConciliacaoPanel\ConciliacaoPanel;
use Framework\Infrastructure\MVC\View\Components\ConciliacaoPanel\ConciliacaoPanelColumn;
use Framework\Infrastructure\MVC\View\Components\ConciliacaoPanel\ConciliacaoPanelFilter;
use Framework\Infrastructure\MVC\View\Components\ConciliacaoPanel\ConciliacaoPanelSide;

abstract class ConciliacaoPanelView extends View
{
    protected function instanciaViewComponent(): void
    {
        $this->setViewComponent(new ConciliacaoPanel());
    }

    protected function getPanel(): ConciliacaoPanel
    {
        return $this->getViewComponent();
    }

    protected function makeColumn(string $name, string $label, string $type, array $options = []): ConciliacaoPanelColumn
    {
        return new ConciliacaoPanelColumn($name, $label, $type, $options);
    }

    protected function makeFilter(string $name, string $label, string $type, array $options = [], mixed $value = null): ConciliacaoPanelFilter
    {
        return new ConciliacaoPanelFilter($name, $label, $type, $options, $value);
    }

    protected function makeSide(string $title): ConciliacaoPanelSide
    {
        return new ConciliacaoPanelSide($title);
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
