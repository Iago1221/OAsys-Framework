<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Relatorio;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Interface\GridView;

class RelatorioGridView extends GridView
{
    protected function create()
    {
        $this->addColumn(new GridField('id', 'ID', Field::TYPE_INTEGER));
        $this->addColumn(new GridField('descricao', 'Descrição', Field::TYPE_TEXT));
    }
}
