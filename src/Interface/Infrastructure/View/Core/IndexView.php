<?php

namespace Framework\Interface\Infrastructure\View\Core;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Interface\View;
use Framework\Infrastructure\MVC\View\Layout\Base;
use Framework\Infrastructure\MVC\View\Layout\Menu;
use Framework\Interface\Application\Auth\AuthorizationService;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioModuloItemRepository;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioModuloRepository;

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
        $this->menu = new Menu(new AuthorizationService(new UsuarioModuloRepository(Main::getConnection()), new UsuarioModuloItemRepository(Main::getConnection())));
        $this->menu->setModulos($this->data['modulos']);
    }

    protected function instanciaViewComponent()
    {

    }

    public function render()
    {
        throw new \DomainException('Não se pode renderizar componentes na raiz!');
    }
}
