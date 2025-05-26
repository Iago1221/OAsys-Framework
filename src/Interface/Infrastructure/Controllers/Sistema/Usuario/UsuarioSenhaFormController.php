<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Usuario;

use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;
use Framework\Interface\Infrastructure\View\Sistema\Usuario\UsuarioSenhaFormView;
use Framework\Interface\Domain\Usuario\Usuario;

class UsuarioSenhaFormController extends FormController
{
    protected bool $gravaLog = false;

    public function editPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->show(false);
            return;
        }

        /** @var Usuario $oUsuario */
        $oUsuario = $this->getRepository()->findBy('id', $this->getRequest('id'));
        $oUsuario->setSenha($this->getRequest('senha'));

        $this->getRepository()->save($oUsuario);
    }

    public function getViewClass(): string
    {
        return UsuarioSenhaFormView::class;
    }

    protected function getRepositoryClass(): string
    {
        return UsuarioRepository::class;
    }
}