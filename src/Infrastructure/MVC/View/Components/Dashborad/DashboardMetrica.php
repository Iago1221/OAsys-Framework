<?php

namespace Framework\Infrastructure\MVC\View\Components\Dashborad;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class DashboardMetrica implements IComponent
{
    private $name;
    private $label;
    private $color;


    public function __construct(string $name, string $label, string $color)
    {
        $this->name = $name;
        $this->label = $label;
        $this->color = $color;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'color' => $this->color,
        ];
    }
}
