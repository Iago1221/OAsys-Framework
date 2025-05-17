<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Usuario;

use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;
use Framework\Interface\Infrastructure\View\Sistema\Usuario\UsuarioFormView;
use Framework\Interface\Domain\Usuario\Usuario;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class UsuarioFormController extends FormController
{
    /**
     * @param Usuario $oModel
     * @return void
     * @throws \Exception
     */
    protected function beforeAdd($oModel)
    {
        parent::beforeAdd($oModel);
        $this->validaAdicionar();
        $oModel->setSenha($this->getRequest(['senha']));
    }

    protected function beforeEdit($oModel)
    {
        parent::beforeEdit($oModel);
        $this->validaEditar();
    }

    public function getViewClass(): string
    {
        return UsuarioFormView::class;
    }

    protected function getRepositoryClass(): string
    {
        return UsuarioRepository::class;
    }

    public function validaAdicionar()
    {
        if ($this->getRepository()->findBy('email', $this->getRequest('email'))) {
            throw new \Exception('E-mail já cadastrado!');
        }
    }

    public function validaEditar()
    {
        if ($aUsuarios = $this->getRepository()->findAllBy('email', $this->getRequest('email'))) {
            /** @var Usuario $oUsuario */
            foreach ($aUsuarios as $oUsuario) {
                if ($oUsuario->getId() != $this->getRequest('id')) {
                    throw new \Exception('E-mail já cadastrado!');
                }
            }
        }
    }
}
