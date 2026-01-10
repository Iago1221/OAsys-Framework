<?php

namespace Framework\Interface\Infrastructure\Controllers\Core;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Layout\Menu;
use Framework\Interface\Infrastructure\Persistence\Sistema\Modulo\ModuloRepository;
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

        $oView = new IndexView($data);

        echo $oView->renderLayout();
    }

    public function toggleSistema()
    {
        $this->moduloRepository->filterBy(['sistema' => $_SESSION['sistema']]);
        $modulos = $this->moduloRepository->get();;
        $menu  = new Menu();
        $menu->setModulos($modulos);
        $menu->render();
        exit;
    }
}
