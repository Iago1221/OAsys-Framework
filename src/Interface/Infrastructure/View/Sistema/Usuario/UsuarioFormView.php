<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Usuario;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Infrastructure\MVC\View\Interface\FormView;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class UsuarioFormView extends FormView
{
    protected function create()
    {
        if (!Main::isRoute('sys_usuario_add')) {
            $this->addComponent(new FormField('id', 'ID', Field::TYPE_INTEGER, true, true));
        }
        $this->addComponent(new FormField('nome', 'Nome', Field::TYPE_TEXT));
        $this->addComponent(new FormField('email', 'Email', Field::TYPE_EMAIL));

        if (Main::isRoute('sys_usuario_add')) {
            $this->addComponent(new FormField('senha', 'Senha', Field::TYPE_PASSWORD));
        }
    }
}
