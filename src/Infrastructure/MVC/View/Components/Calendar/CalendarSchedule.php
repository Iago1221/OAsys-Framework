<?php

namespace Framework\Infrastructure\MVC\View\Components\Calendar;

class CalendarSchedule
{
    protected $id;
    protected $nome;
    protected $color;

    public function __construct($id, $nome, $color = null)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->color = $color;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'color' => $this->color
        ];
    }
}
