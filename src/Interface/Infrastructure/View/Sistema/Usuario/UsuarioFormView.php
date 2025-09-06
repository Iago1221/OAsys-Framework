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

        $this->addComponent(new FormField('acessoErp', 'Possui acesso ERP?', Field::TYPE_CHECK, false));
        $this->addComponent(new FormField('acessoCrm', 'Possui acesso CRM?', Field::TYPE_CHECK, false));
        $this->addComponent(new FormField('acessoGestao', 'Possui acesso Gestão?', Field::TYPE_CHECK, false));
        $this->addComponent(new FormField('acessoVarejo', 'Possui acesso Varejo?', Field::TYPE_CHECK, false));
        $this->addComponent(new FormField('acessoIndustria', 'Possui acesso Indústria?', Field::TYPE_CHECK, false));
        $this->addComponent(new FormField('acessoNeuron', 'Possui acesso Neuron?', Field::TYPE_CHECK, false));

        if (Main::isRoute('sys_usuario_add')) {
            $this->addComponent(new FormField('senha', 'Senha', Field::TYPE_PASSWORD));
        }
    }
}
