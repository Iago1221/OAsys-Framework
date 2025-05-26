<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Modulo;

use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Modulo\ModuloRepository;
use Framework\Interface\Infrastructure\View\Sistema\Modulo\ModuloFormView;

class ModuloFormController extends FormController
{
    protected bool $gravaLog = false;

    protected function getViewClass(): string
    {
        return ModuloFormView::class;
    }

    protected function getRepositoryClass(): string
    {
        return ModuloRepository::class;
    }
}
