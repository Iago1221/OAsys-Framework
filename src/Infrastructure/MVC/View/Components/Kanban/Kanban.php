<?php

namespace Framework\Infrastructure\MVC\View\Components\Kanban;

use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Components\Fields\GridFilter;
use Framework\Infrastructure\MVC\View\Components\IComponent;

class Kanban implements IComponent
{
    private array $cols = [];
    private array $actions = [];
    private $addRoute;

    public function addColumn($id, $title, $items = [])
    {
        $this->cols[] = ['id' => $id, 'title' => $title, 'items' => $items];
    }

    public function addAction($name, $label, $route, $httpMethod = 'GET', $blank = false)
    {
        $this->actions[] = ['route' => $route, 'name' => $name, 'label' => $label, 'httpMethod' => $httpMethod, 'blank' => $blank];
    }

    public function setAddRoute($route)
    {
        $this->addRoute = $route;
    }

    public function toArray(): array
    {
        $data = [
            'component' => 'KanbanComponent',
            'KanbanComponent' => [
                'disabled' => false,
                'cols' => $this->cols,
                'actions' => $this->actions,
                'addRoute' => $this->addRoute
            ]
        ];

        return $data;
    }
}
