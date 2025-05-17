<?php

namespace Framework\Interface\Infrastructure\Controllers\Core;

use Framework\Core\Main;
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
        $data['modulos'] = $this->moduloRepository->get();

        $oView = new IndexView($data);
        echo $oView->renderLayout();
    }
}
