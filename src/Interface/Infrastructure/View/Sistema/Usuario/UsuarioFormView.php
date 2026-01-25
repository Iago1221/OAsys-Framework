<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Usuario;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Infrastructure\MVC\View\Components\Fields\GridForm;
use Framework\Infrastructure\MVC\View\Components\Fields\Tab;
use Framework\Infrastructure\MVC\View\Interface\FormView;
use Framework\Interface\Domain\Modulo\Modulo;
use Framework\Interface\Domain\Usuario\UsuarioModulo;
use Framework\Interface\Domain\Usuario\UsuarioModuloItem;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class UsuarioFormView extends FormView
{
    protected Tab $tab;

    protected function create()
    {
        $this->setWidth('1200px');
        $this->setFormLayout(self::FORM_LAYOUT_ONE_COLUMN);
        $this->tab = new Tab('usuario');
        $this->tab->addTab('Geral');

        if (!Main::isRoute('sys_usuario_add')) {
            $this->tab->addComponent(new FormField('id', 'ID', Field::TYPE_INTEGER, true, true));
        }
        $this->tab->addComponent(new FormField('nome', 'Nome', Field::TYPE_TEXT));
        $this->tab->addComponent(new FormField('email', 'Email', Field::TYPE_EMAIL));

        $this->tab->addComponent(new FormField('acessoErp', 'Possui acesso ERP?', Field::TYPE_CHECK, false));
        $this->tab->addComponent(new FormField('acessoCrm', 'Possui acesso CRM?', Field::TYPE_CHECK, false));
        $this->tab->addComponent(new FormField('acessoGestao', 'Possui acesso Gestão?', Field::TYPE_CHECK, false));
        $this->tab->addComponent(new FormField('acessoVarejo', 'Possui acesso Varejo?', Field::TYPE_CHECK, false));
        $this->tab->addComponent(new FormField('acessoIndustria', 'Possui acesso Indústria?', Field::TYPE_CHECK, false));
        $this->tab->addComponent(new FormField('acessoNeuron', 'Possui acesso Neuron?', Field::TYPE_CHECK, false));

        if (Main::isRoute('sys_usuario_add')) {
            $this->tab->addComponent(new FormField('senha', 'Senha', Field::TYPE_PASSWORD));
        }

        $this->tab->addTab('Permissões');

        $this->addComponent($this->tab);
    }

    private function indexPermissaoModulo(array $permissoes): array
    {
        $map = [];

        foreach ($permissoes as $permissao) {
            $map[$permissao->getModulo()] = $permissao;
        }

        return $map;
    }

    private function indexPermissaoItem(array $permissoes): array
    {
        $map = [];

        foreach ($permissoes as $permissao) {
            $map[$permissao->getModuloItem()] = $permissao;
        }

        return $map;
    }

    /**
     * @param Modulo[] $modulos
     * @param UsuarioModulo[] $permissaoModulos
     * @param UsuarioModuloItem[] $permissaoItens
     */
    public function setPrivilegioFields(array $modulos, array $permissaoModulos, array $permissaoItens): void {
        $permissaoModuloMap = $this->indexPermissaoModulo($permissaoModulos);
        $permissaoItemMap   = $this->indexPermissaoItem($permissaoItens);

        foreach ($modulos as $modulo) {
            $grid = new GridForm($modulo->getId(), $modulo->getTitulo());
            $grid->disableControls();

            $grid->addField(new FormField('moduloId', 'Modulo', Field::TYPE_INTEGER, true, true));
            $grid->addField(new FormField('moduloTitulo', 'Título do Modulo', Field::TYPE_TEXT, true, true));
            $grid->addField(new FormField('permitido', 'Permitido', Field::TYPE_CHECK, false));
            $grid->setFieldset();

            $permissaoModulo = $permissaoModuloMap[$modulo->getId()] ?? null;
            $moduloPermitido = $permissaoModulo
                ? $permissaoModulo->getPermitido()
                : true;

            $values = [
                'moduloId'     => $modulo->getId(),
                'moduloTitulo' => $modulo->getTitulo(),
                'permitido'    => $moduloPermitido,
            ];

            foreach ($modulo->getItens() as $item) {
                $grid->addFieldset($item->getId(), $item->getTitulo());
                $grid->addFieldsetField(
                    $item->getId(),
                    new FormField("{$item->getId()}ItemId", 'Item', Field::TYPE_INTEGER, true, true)
                );
                $grid->addFieldsetField(
                    $item->getId(),
                    new FormField("{$item->getId()}ItemTitulo", 'Título do Item', Field::TYPE_TEXT, true, true)
                );
                $grid->addFieldsetField(
                    $item->getId(),
                    new FormField("{$item->getId()}Permitido", 'Permitido', Field::TYPE_CHECK, false)
                );

                $permissaoItem = $permissaoItemMap[$item->getId()] ?? null;
                $itemPermitido = $permissaoItem
                    ? $permissaoItem->getPermitido()
                    : true;

                $values["{$item->getId()}ItemId"]      = $item->getId();
                $values["{$item->getId()}ItemTitulo"] = $item->getTitulo();
                $values["{$item->getId()}Permitido"]  = $itemPermitido;
            }

            $grid->setValue($values);
            $this->tab->addComponent($grid);
        }
    }
}
