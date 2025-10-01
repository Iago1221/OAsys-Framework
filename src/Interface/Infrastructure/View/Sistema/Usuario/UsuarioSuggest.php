<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Usuario;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Infrastructure\MVC\View\Components\Fields\SuggestField;

class UsuarioSuggest extends SuggestField
{
    public function __construct(bool $required = false, string  $sName = 'usuario', string $sLabel = 'Usuário')
    {
        parent::__construct(
            $sName,
            new FormField('id', 'Usuário', Field::TYPE_INTEGER, $required),
            new FormField('nome', 'Nome', Field::TYPE_TEXT, false),
            $sLabel,
            'sys_usuario'
        );
    }
}
