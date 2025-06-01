<?php

namespace Framework\Infrastructure\MVC\View\Interface;

use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Components\Fields\GridFilter;
use Framework\Infrastructure\MVC\View\Components\Grid\Grid;

abstract class GridView extends View
{
    protected function instanciaViewComponent()
    {
        $this->setViewComponent(new Grid());
    }

    protected function addColumn(GridField $field, $filter = true)
    {
        $this->getViewComponent()->addColumn($field);

        if ($filter) {
            $this->getViewComponent()->addFilter(GridFilter::fromGridField($field));
        }
    }

    protected function addFilter(GridFilter $filter)
    {
        $this->getViewComponent()->addFilter($filter);
    }

    public function addAction($name, $label, $route, $httpMethod = 'GET')
    {
        $this->getViewComponent()->addAction($name, $label, $route, $httpMethod);
    }

    protected function addDefaultActions($routeName, $add = true, $show = true, $edit = true, $delete = true, $changeStatus = false)
    {
        if ($add) {
            $this->addGridAction('add', 'Adicionar', 'sys_'.$routeName.'_add');
        }

        if ($show) {
            $this->addAction('show', 'Visualizar', 'sys_'.$routeName.'_show');
        }

        if ($edit) {
            $this->addAction('edit', 'Editar', 'sys_'.$routeName.'_edit');
        }

        if ($delete) {
            $this->addAction('delete', 'Excluir', 'sys_'.$routeName.'_delete', 'DELETE');
        }

        if ($changeStatus) {
            $this->addAction('status', 'Ativar/Inativar', 'sys_'.$routeName.'_status');
        }
    }

    protected function addGridAction($name, $label, $route, $httpMethod = 'GET')
    {
        $this->getViewComponent()->addGridAction($name, $label, $route, $httpMethod);
    }

    public function render($aData = [])
    {
        $window = [
            'window' => [
                'title' => $this->getTitulo(),
                'route' => $this->getRota()
            ]
        ];

        $component = array_merge($window, $this->getViewComponent()->toArray());

        echo json_encode($component);
    }

    private function atualizaValorFiltros($valores)
    {
        /** @var GridFilter $filter */
        foreach ($this->getViewComponent()->getFilters() as $filter) {
            foreach ($valores as $key => $value) {
                if ($filter->getName() == $key) {
                    $filter->setVaLue($value);
                }
            }
        }
    }
}
