<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Usuario;

use Framework\Core\Main;
use Framework\Infrastructure\DB\Persistence\Storage\Repository\GenericRepository;
use Framework\Infrastructure\MVC\Controller\GridController;
use Framework\Infrastructure\MVC\View\Interface\View;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioMapper;
use Framework\Interface\Infrastructure\View\Sistema\Usuario\UsuarioGridView;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class UsuarioGridController extends GridController
{
    public function getView(): View
    {
        return new UsuarioGridView();
    }

    protected function setMapper(): void
    {
        $this->oMapper = new UsuarioMapper(new GenericRepository(Main::getPdoStorage()));
    }
}
