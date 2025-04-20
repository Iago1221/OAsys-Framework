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
            $this->addComponent(new FormField('iId', 'ID', Field::TYPE_NUMBER, true, true));
        }
        $this->addComponent(new FormField('sNome', 'Nome', Field::TYPE_TEXT));
        $this->addComponent(new FormField('sEmail', 'Email', Field::TYPE_EMAIL));

        if (Main::isRoute('sys_usuario_add')) {
            $this->addComponent(new FormField('sSenha', 'Senha', Field::TYPE_PASSWORD));
        }
    }
}
