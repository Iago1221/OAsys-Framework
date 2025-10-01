<?php

namespace Framework\Infrastructure\MVC\View\Interface;

use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Components\Fields\GridFilter;
use Framework\Infrastructure\MVC\View\Components\Grid\Grid;
use Framework\Infrastructure\MVC\View\Components\Kanban\Kanban;
use Framework\Infrastructure\MVC\View\Components\Kanban\KanbanCol;

abstract class KanbanView extends View
{
    protected function instanciaViewComponent()
    {
        $this->setViewComponent(new Kanban());
    }

    protected function addColumn(KanbanCol $col)
    {
        $this->getViewComponent()->addColumn($col->getId(), $col->getNome(), $col->getItems());
    }

    public function addAction($name, $label, $route, $httpMethod = 'GET', $blank = false)
    {
        $this->getViewComponent()->addAction($name, $label, $route, $httpMethod, $blank);
    }

    public function addRoute($route)
    {
        $this->getViewComponent()->setAddRoute($route);
    }

    public function render($aData = [])
    {
        $window = [
            'window' => [
                'title' => $this->getTitulo(),
                'route' => $this->getRota()
            ]
        ];

        $component = array_merge($window, $this->getViewComponent()->toArray());

        echo json_encode($component);
    }
}
