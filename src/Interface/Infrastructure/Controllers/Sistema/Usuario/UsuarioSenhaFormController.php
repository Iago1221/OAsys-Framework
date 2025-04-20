<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Usuario;

use Framework\Core\Main;
use Framework\Infrastructure\DB\Persistence\Storage\Repository\GenericRepository;
use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Infrastructure\MVC\View\Interface\View;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioMapper;
use Framework\Interface\Infrastructure\View\Sistema\Usuario\UsuarioSenhaFormView;
use Framework\Interface\Domain\Usuario\Usuario;

class UsuarioSenhaFormController extends FormController
{
    public function editPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->show(false);
            return;
        }

        $aData = $this->getRequest();

        /** @var Usuario $oUsuario */
        $oUsuario = $this->getMapper()->find(['iId' => $aData['iId']]);
        $oUsuario->setSenha($aData['sSenha']);

        $this->getMapper()->save($oUsuario);
    }

    public function getView(): View
    {
        return new UsuarioSenhaFormView();
    }

    protected function setMapper(): void
    {
        $this->oMapper =  new UsuarioMapper(new GenericRepository(Main::getPdoStorage()));
    }
}