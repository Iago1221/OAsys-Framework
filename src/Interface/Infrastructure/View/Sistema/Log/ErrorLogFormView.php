<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Log;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Infrastructure\MVC\View\Interface\FormView;

class ErrorLogFormView extends FormView
{
    protected function create()
    {
        $this->addComponent(new FormField('usuario', 'Usuário ID', Field::TYPE_INTEGER));
        $this->addComponent(new FormField('rota', 'Rota ID', Field::TYPE_INTEGER));
        $this->addComponent(new FormField('data', 'Data', Field::TYPE_DATETIME));
        $this->addComponent(new FormField('arquivo', 'Arquivo', Field::TYPE_TEXT));
        $this->addComponent(new FormField('erro', 'Erro', Field::TYPE_TEXTAREA));
        $this->addComponent(new FormField('trace', 'Trace', Field::TYPE_TEXTAREA));
    }
}
