<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Modulo;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Interface\GridView;

class ModuloGridView extends GridView
{
    protected function create()
    {
        $this->addColumn(new GridField('iId', 'ID', Field::TYPE_NUMBER));
        $this->addColumn(new GridField('sTitulo', 'Título', Field::TYPE_TEXT));
    }
}