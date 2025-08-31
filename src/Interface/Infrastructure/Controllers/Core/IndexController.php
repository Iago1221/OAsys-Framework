<?php

namespace Framework\Interface\Infrastructure\Controllers\Core;

use Framework\Core\Main;
use Framework\Interface\Domain\Modulo\Modulo;
use Framework\Interface\Domain\Usuario\Usuario;
use Framework\Interface\Infrastructure\Persistence\Sistema\Modulo\ModuloRepository;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;
use Framework\Interface\Infrastructure\View\Core\IndexView;

class IndexController
{
    private $moduloRepository;

    public function __construct()
    {
        $this->moduloRepository = new ModuloRepository(Main::getConnection());
    }

    public function index() {
        $data = [];
        $this->moduloRepository->filterBy(['sistema' => $_SESSION['sistema']]);
        $data['modulos'] = $this->moduloRepository->get();

        /** @var Usuario $usuario */
        $usuario = (new UsuarioRepository(Main::getConnection()))->findBy('id', Main::getUsuarioId());

        $data['possuiAcessoErp'] = $usuario->getAcessoErp();
        $data['possuiAcessoCrm'] = $usuario->getAcessoCrm();

        $oView = new IndexView($data);

        if (isset($_GET['system'])) {
            echo $oView->renderBody();
            return;
        }

        echo $oView->renderLayout();
    }

    public function toggleSistema() {
        if ($_SESSION['sistema'] == Modulo::SISTEMA_ERP) {
            $_SESSION['sistema'] = Modulo::SISTEMA_CRM;
            $this->index();
            return;
        }

        $_SESSION['sistema'] = Modulo::SISTEMA_ERP;
        $this->index();
    }
}
