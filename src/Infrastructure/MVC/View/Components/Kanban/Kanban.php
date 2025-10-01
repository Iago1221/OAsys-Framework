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
    private $getItensRoute;
    private $trocaCardColunaRoute;

    public function addColumn($id, $title, $concluiCard = false)
    {
        $this->cols[] = ['id' => $id, 'title' => $title, 'concluiCard' => $concluiCard];
    }

    public function addAction($name, $label, $hint, $route, $httpMethod = 'GET', $blank = false)
    {
        $this->actions[] = ['route' => $route, 'name' => $name, 'label' => $label, 'hint' => $hint, 'httpMethod' => $httpMethod, 'blank' => $blank];
    }

    public function setGetItensRoute($route)
    {
        $this->getItensRoute = $route;
    }

    public function setAddRoute($route)
    {
        $this->addRoute = $route;
    }

    public function setTrocaCardColunaRoute($route)
    {
        $this->trocaCardColunaRoute = $route;
    }

    public function toArray(): array
    {
        $data = [
            'component' => 'KanbanComponent',
            'KanbanComponent' => [
                'disabled' => false,
                'cols' => $this->cols,
                'actions' => $this->actions,
                'addRoute' => $this->addRoute,
                'getItensRoute' => $this->getItensRoute,
                'trocaCardColunaRoute' => $this->trocaCardColunaRoute
            ]
        ];

        return $data;
    }
}
