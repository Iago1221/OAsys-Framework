<?php

namespace Framework\Infrastructure\MVC\View\Components\Timeline;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class TimelineItem implements IComponent
{
    private string $data;
    private string $titulo;
    private ?string $descricao;
    private ?string $color;

    public function __construct(
        string $titulo,
        ?string $descricao = null,
        ?string $data = null,
        ?string $color = '#007bff'
    ) {
        $this->data = $data;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->color = $color;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'color' => $this->color
        ];
    }
}
