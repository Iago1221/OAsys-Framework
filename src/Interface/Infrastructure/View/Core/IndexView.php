<?php

namespace Framework\Interface\Infrastructure\View\Core;

use Framework\Infrastructure\MVC\View\Interface\View;
use Framework\Infrastructure\MVC\View\Layout\Base;
use Framework\Infrastructure\MVC\View\Layout\Menu;

class IndexView extends View
{
    private $menu;

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->setLayout(new Base($this->getMenu()));
    }

    private function getMenu()
    {
        return $this->menu;
    }

    protected function create()
    {
        $this->menu = new Menu();
        $this->menu->setModulos($this->data['modulos']);
    }

    protected function instanciaComponent()
    {

    }

    public function render()
    {
        throw new \DomainException('Não se pode renderizar componentes na raiz!');
    }
}
