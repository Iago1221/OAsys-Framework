<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Usuario;

use Framework\Core\Main;
use Framework\Infrastructure\DB\Persistence\Storage\Repository\GenericRepository;
use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Infrastructure\MVC\View\Interface\View;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioMapper;
use Framework\Interface\Infrastructure\View\Sistema\Usuario\UsuarioFormView;
use Framework\Interface\Usuario\Usuario;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class UsuarioFormController extends FormController
{
    public function add()
    {
        $this->validaAdicionar();

        if (!($_SERVER['REQUEST_METHOD'] == 'GET')) {
            $this->setParam('sSenha', password_hash($_REQUEST['data']['sSenha'], PASSWORD_ARGON2ID));
        }

        parent::add();
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->show(false);
            return;
        }

        $this->validaEditar();
        parent::edit();
    }

    public function getView(): View
    {
        return new UsuarioFormView();
    }

    protected function setMapper(): void
    {
        $this->oMapper = new UsuarioMapper(new GenericRepository(Main::getPdoStorage()));
    }

    public function validaAdicionar()
    {
        if ($this->getMapper()->exists(['sEmail' => $this->getRequest('sEmail')])) {
            throw new \Exception('E-mail já cadastrado!');
        }
    }

    public function validaEditar()
    {
        if ($aUsuarios = $this->getMapper()->get(['sEmail' => $this->getRequest('sEmail')])) {
            /** @var Usuario $oUsuario */
            foreach ($aUsuarios as $oUsuario) {
                if ($oUsuario->getId() != $this->getRequest('iId')) {
                    throw new \Exception('E-mail já cadastrado!');
                }
            }
        }
    }
}
