<?php

namespace Framework\Infrastructure\MVC\View\Components\Kanban;

use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Components\Fields\GridFilter;
use Framework\Infrastructure\MVC\View\Components\IComponent;

class Kanban implements IComponent
{
    private array $cols = [];

    public function addColumn($id, $title, $items = [])
    {
        $this->cols[] = ['id' => $id, 'title' => $title, 'items' => $items];
    }

    public function toArray(): array
    {
        $data = [
            'component' => 'KanbanComponent',
            'KanbanComponent' => [
                'disabled' => false,
                'cols' => $this->cols
            ]
        ];

        return $data;
    }
}
