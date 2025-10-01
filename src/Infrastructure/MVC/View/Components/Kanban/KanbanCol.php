<?php

namespace Framework\Infrastructure\MVC\View\Components\Kanban;

class KanbanCol
{
    private $id;
    private $nome;
    private $concluiCard;

    public function __construct($id, $nome)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->concluiCard = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getConcluiCard()
    {
        return $this->concluiCard;
    }

    public function setConcluiCard($concluiCard = true)
    {
        $this->concluiCard = $concluiCard;
    }
}
