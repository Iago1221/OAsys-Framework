<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Log;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Interface\GridView;

class ErrorLogGridView extends GridView
{
    protected function create()
    {
        $this->addColumn(new GridField('usuario/id', 'Usuário ID', Field::TYPE_INTEGER));
        $this->addColumn(new GridField('usuario/nome', 'Usuário', Field::TYPE_TEXT));
        $this->addColumn(new GridField('rota/id', 'Rota ID', Field::TYPE_INTEGER));
        $this->addColumn(new GridField('rota/nome', 'Rota', Field::TYPE_TEXT));
        $this->addColumn(new GridField('data', 'Data', Field::TYPE_DATETIME));
        $this->addColumn(new GridField('erro', 'Erro', Field::TYPE_TEXT));

        $this->addAction('show', 'Visualizar', 'sys_error_log_show');
    }
}
