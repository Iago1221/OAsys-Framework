<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Modulo;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Interface\GridView;
use Framework\Interface\Domain\Modulo\Modulo;

class ModuloGridView extends GridView
{
    protected function create()
    {
        $this->addColumn(new GridField('id', 'ID', Field::TYPE_INTEGER));
        $this->addColumn(new GridField('titulo', 'Título', Field::TYPE_TEXT));

        $fieldSituacao = new GridField('situacao', 'Situação', Field::TYPE_LIST);
        $fieldSituacao->addOption(Modulo::SITUACAO_ATIVO,  'Ativo');
        $fieldSituacao->addOption(Modulo::SITUACAO_INATIVO,  'Inativo');

        $this->addColumn($fieldSituacao);

        $this->getViewComponent()->getFilter('situacao')->setOptions($fieldSituacao->getOptions());

        $this->addAction('show', 'Visualizar', 'sys_modulo_show');
        $this->addAction('edit', 'Editar', 'sys_modulo_edit');
    }
}
