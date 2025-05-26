<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Modulo;

use Framework\Infrastructure\MVC\Controller\GridController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Modulo\ModuloRepository;
use Framework\Interface\Infrastructure\View\Sistema\Modulo\ModuloGridView;

class ModuloGridController extends GridController
{
    protected function getViewClass(): string
    {
        return ModuloGridView::class;
    }

    protected function getRepositoryClass(): string
    {
        return ModuloRepository::class;
    }
}
