<?php

namespace Framework\Interface\Infrastructure\Controllers\Core;

use Framework\Core\Main;
use Framework\Infrastructure\DB\Persistence\Storage\Repository\GenericRepository;
use Framework\Interface\Domain\Modulo\Modulo;
use Framework\Interface\Domain\Modulo\ModuloItem;
use Framework\Interface\Infrastructure\Persistence\Sistema\Modulo\ModuloItemMapper;
use Framework\Interface\Infrastructure\Persistence\Sistema\Modulo\ModuloMapper;
use Framework\Interface\Infrastructure\View\Core\IndexView;

class IndexController
{
    private $oModuloMapper;
    private $oModuloItemMapper;

    public function __construct()
    {
        $oGenericRepository = new GenericRepository(Main::getPdoStorage());
        $this->oModuloMapper = new ModuloMapper($oGenericRepository);
        $this->oModuloItemMapper = new ModuloItemMapper($oGenericRepository);
    }

    public function index() {
        /** @var Modulo[] $aModulos */
        $aModulos = $this->oModuloMapper->get();
        $aData = [];

        foreach ($aModulos as $oModulo) {
            /** @var ModuloItem[] $aItens */
            $aItens = $this->oModuloItemMapper->get(['iModulo' => $oModulo->getId()]);
            $oModulo->setItens($aItens);
            $aData['modulos'][] = $oModulo;
        }

        $oView = new IndexView($aData);
        echo $oView->renderLayout();
    }
}
