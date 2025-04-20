<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Usuario;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Infrastructure\MVC\View\Interface\FormView;

class UsuarioSenhaFormView extends FormView
{
    public function create()
    {
        $this->addComponent(new FormField('iId', 'ID', Field::TYPE_NUMBER, true, true));
        $this->addComponent(new FormField('sSenha', 'Senha', Field::TYPE_PASSWORD));
    }
}
