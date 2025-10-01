<?php

namespace Framework\Infrastructure\MVC\View\Components\Kanban;

class KanbanCol
{
    private $id;
    private $nome;
    private $items;

    public function __construct($id, $nome)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->items = [];
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }
}
