<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Log;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Interface\GridView;

class LogGridView extends GridView
{
    protected function create()
    {
        $this->addColumn(new GridField('route', 'Rota', Field::TYPE_TEXT));
        $this->addColumn(new GridField('usuario', 'Usuário ID', Field::TYPE_INTEGER));
        $this->addColumn(new GridField('data', 'Data', Field::TYPE_DATETIME));

        $this->addAction('show', 'Visualizar', 'sys_log_show');
    }
}
