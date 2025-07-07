<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Relatorio;

use Framework\Infrastructure\MVC\Controller\GridController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Relatorio\RelatorioRepository;
use Framework\Interface\Infrastructure\View\Sistema\Relatorio\RelatorioGridView;

abstract class RelatorioGridController extends GridController
{
    protected function getViewClass(): string
    {
        return RelatorioGridView::class;
    }

    protected function getRepositoryClass(): string
    {
        return RelatorioRepository::class;
    }

    public function list()
    {
        $this->getView()->addAction('imprimir', 'Imprimir', "sys_". strtolower($this->getPacote())."_report");
        $this->getRepository()->filterBy(['pacote' => $this->getPacote()]);
        parent::list();
    }

    /** @return string */
    abstract protected function getPacote();
}
