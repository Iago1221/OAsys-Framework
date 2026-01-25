<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Usuario;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Interface\Domain\Modulo\Modulo;
use Framework\Interface\Domain\Usuario\UsuarioModulo;
use Framework\Interface\Domain\Usuario\UsuarioModuloItem;
use Framework\Interface\Infrastructure\Persistence\Sistema\Modulo\ModuloRepository;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioModuloItemRepository;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioModuloRepository;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;
use Framework\Interface\Infrastructure\View\Sistema\Usuario\UsuarioFormView;
use Framework\Interface\Domain\Usuario\Usuario;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class UsuarioFormController extends FormController
{
    protected ModuloRepository $moduloRepository;
    protected UsuarioModuloRepository $usuarioModuloRepository;
    protected UsuarioModuloItemRepository $usuarioModuloItemRepository;

    protected function getModuloRepository(): ModuloRepository
    {
        if (!isset($this->moduloRepository)) {
            $this->moduloRepository = new ModuloRepository(Main::getConnection(), false);
        }

        return $this->moduloRepository;
    }

    protected function getUsuarioModuloRepository(): UsuarioModuloRepository
    {
        if (!isset($this->usuarioModuloRepository)) {
            $this->usuarioModuloRepository = new UsuarioModuloRepository(Main::getConnection());
        }

        return $this->usuarioModuloRepository;
    }

    protected function getUsuarioModuloItemRepository(): UsuarioModuloItemRepository
    {
        if (!isset($this->usuarioModuloItemRepository)) {
            $this->usuarioModuloItemRepository = new UsuarioModuloItemRepository(Main::getConnection());
        }

        return $this->usuarioModuloItemRepository;
    }

    /**
     * @param Usuario $oModel
     * @return void
     * @throws \Exception
     */
    protected function beforeAdd($oModel)
    {
        parent::beforeAdd($oModel);
        $this->validaAdicionar();
        $oModel->setSenha($this->getRequest('senha'));
        $this->inserePrivilegiosUsuario($oModel);
    }

    protected function beforeEdit($oModel)
    {
        parent::beforeEdit($oModel);
        $this->validaEditar();
        $this->apagarPrivilegiosUsuario($oModel);
        $this->inserePrivilegiosUsuario($oModel);
    }

    protected function apagarPrivilegiosUsuario($oModel)
    {
        $privilegioModulos = $this->getUsuarioModuloRepository()->findAllBy('usuario', $oModel->getId());
        $privilegioItens = $this->getUsuarioModuloItemRepository()->findAllBy('usuario', $oModel->getId());

        foreach ($privilegioItens as $privilegioItem) {
            $this->getUsuarioModuloItemRepository()->remove($privilegioItem);
        }

        foreach ($privilegioModulos as $privilegioModulo) {
            $this->getUsuarioModuloRepository()->remove($privilegioModulo);
        }
    }

    protected function inserePrivilegiosUsuario($oModel)
    {
        /** @var Modulo[] $modulos */
        $modulos = $this->getModuloRepository()->get();

        foreach ($modulos as $modulo) {
            $requestData = $this->getRequest($modulo->getId());

            if (isset($requestData) && isset($requestData[0])) {
                $requestData = $requestData[0];

                $moduloPermissao = new UsuarioModulo();
                $moduloPermissao->setUsuario($oModel->getId());
                $moduloPermissao->setModulo($modulo->getId());
                $moduloPermissao->setPermitido($requestData['moduloPermitido']);
                $this->getUsuarioModuloRepository()->save($moduloPermissao);

                foreach ($modulo->getItens() as $item) {
                    if (isset($requestData["{$item->getId()}ItemPermitido"])) {
                        $itemPermissao = new UsuarioModuloItem();
                        $itemPermissao->setUsuario($oModel->getId());
                        $itemPermissao->setModuloItem($item->getId());
                        $itemPermissao->setPermitido($requestData["{$item->getId()}ItemPermitido"]);
                        $this->getUsuarioModuloItemRepository()->save($itemPermissao);
                    }
                }
            }
        }
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

    protected function beforeRender($oModel, &$aData)
    {
        parent::beforeRender($oModel, $aData);

        $modulos = $this->getModuloRepository()->get();
        $permissaoModulos = $this->getUsuarioModuloRepository()->findAllBy('usuario', $oModel->getId());
        $permissaoItens = $this->getUsuarioModuloItemRepository()->findAllBy('usuario', $oModel->getId());

        var_dump($oModel);
        var_dump($permissaoItens);

        $this->getView()->setPrivilegioFields($modulos, $permissaoModulos, $permissaoItens);
    }
}
