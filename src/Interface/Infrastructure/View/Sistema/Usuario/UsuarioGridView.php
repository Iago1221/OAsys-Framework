<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Usuario;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Interface\GridView;

class UsuarioGridView extends GridView
{
    protected function create()
    {
        $this->addColumn(new GridField('iId', 'ID', Field::TYPE_NUMBER));
        $this->addColumn(new GridField('sNome', 'Nome', Field::TYPE_TEXT));
        $this->addColumn(new GridField('sEmail', 'E-mail', Field::TYPE_EMAIL));

        $this->addDefaultActions('usuario');
        $this->addAction('edit_password', 'Editar senha', 'sys_usuario_edit_password');
    }
}
