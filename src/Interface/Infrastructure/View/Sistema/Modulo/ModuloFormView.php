<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Modulo;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Infrastructure\MVC\View\Interface\FormView;
use Framework\Interface\Domain\Modulo\Modulo;

class ModuloFormView extends FormView
{
    protected function create()
    {

        $this->addComponent(new FormField('id', 'ID', Field::TYPE_NUMBER, true, true));
        $this->addComponent(new FormField('titulo', 'Título', Field::TYPE_TEXT, true, true));
        $fieldSituacao = $this->addComponent(new FormField('situacao', 'Situação', Field::TYPE_LIST, true));
        $fieldSituacao->addOption(Modulo::SITUACAO_ATIVO,  'Ativo');
        $fieldSituacao->addOption(Modulo::SITUACAO_INATIVO,  'Inativo');
    }
}
