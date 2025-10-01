<?php

namespace Framework\Infrastructure\MVC\View\Components\Kanban;

class KanbanCol
{
    private $id;
    private $nome;

    public function __construct($id, $nome)
    {
        $this->id = $id;
        $this->nome = $nome;
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
