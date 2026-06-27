<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Log;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Infrastructure\MVC\View\Interface\FormView;

class RouteLogFormView extends FormView
{
    protected function create()
    {
        $this->addComponent(new FormField('usuario/id', 'Usuário ID', Field::TYPE_INTEGER));
        $this->addComponent(new FormField('usuario/nome', 'Usuário', Field::TYPE_TEXT));
        $this->addComponent(new FormField('rota/id', 'Rota ID', Field::TYPE_INTEGER));
        $this->addComponent(new FormField('rota/nome', 'Rota', Field::TYPE_TEXT));
        $this->addComponent(new FormField('data', 'Data', Field::TYPE_DATETIME));
    }
}
