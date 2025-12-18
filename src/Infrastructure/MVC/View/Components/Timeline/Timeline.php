<?php

namespace Framework\Infrastructure\MVC\View\Components\Timeline;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class Timeline implements IComponent
{
    private string $name;
    private string $titulo;
    private array $items = [];
    private ?string $route = null;

    public function __construct(string $name, string $titulo)
    {
        $this->name = $name;
        $this->titulo = $titulo;
    }

    public function bean() {}
    public function setDisabled() {}

    public function getName(): string
    {
        return $this->name;
    }

    public function addItem(TimelineItem $item): void
    {
        $this->items[] = $item;
    }

    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    public function toArray(): array
    {
        return [
            'component' => 'TimelineComponent',
            'TimelineComponent' => [
                'titulo' => $this->titulo,
                'route' => $this->route,
                'items' => array_map(
                    fn($item) => json_encode($item->toArray()),
                    $this->items
                )
            ]
        ];
    }
}
