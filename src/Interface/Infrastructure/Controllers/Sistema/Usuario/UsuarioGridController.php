<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Usuario;

use Framework\Infrastructure\MVC\Controller\GridController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;
use Framework\Interface\Infrastructure\View\Sistema\Usuario\UsuarioGridView;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class UsuarioGridController extends GridController
{
    public function getViewClass(): string
    {
        return UsuarioGridView::class;
    }

    protected function getRepositoryClass(): string
    {
        return UsuarioRepository::class;
    }
}
