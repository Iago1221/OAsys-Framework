<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Log;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Infrastructure\MVC\View\Interface\FormView;

class LogFormView extends FormView
{
    protected function create()
    {
        $this->addComponent(new FormField('route', 'Rota', Field::TYPE_TEXT));
        $this->addComponent(new FormField('usuarioId', 'Usuário ID', Field::TYPE_NUMBER));
        $this->addComponent(new FormField('data', 'Data', Field::TYPE_DATE));
        $this->addComponent(new FormField('dados', 'Dados', Field::TYPE_TEXTAREA));
    }
}
