<?php

namespace Framework\Infrastructure\MVC\View\Interface;

use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Components\Fields\GridFilter;
use Framework\Infrastructure\MVC\View\Components\Grid\Grid;

abstract class GridView extends View
{
    protected function instanciaComponent()
    {
        $this->setComponent(new Grid());
    }

    protected function addColumn(GridField $field, $filter = true)
    {
        $this->getComponent()->addColumn($field);

        if ($filter) {
            $this->getComponent()->addFilter(GridFilter::fromGridField($field));
        }
    }

    protected function addFilter(GridFilter $filter)
    {
        $this->getComponent()->addFilter($filter);
    }

    public function addAction($name, $label, $route, $httpMethod = 'GET')
    {
        $this->getComponent()->addAction($name, $label, $route, $httpMethod);
    }

    protected function addDefaultActions($routeName)
    {
        $this->addGridAction('add', 'Adicionar', 'sys_'.$routeName.'_add');
        $this->addAction('show', 'Visualizar', 'sys_'.$routeName.'_show');
        $this->addAction('edit', 'Editar', 'sys_'.$routeName.'_edit');
        $this->addAction('delete', 'Excluir', 'sys_'.$routeName.'_delete', 'DELETE');
    }

    protected function addGridAction($name, $label, $route, $httpMethod = 'GET')
    {
        $this->getComponent()->addGridAction($name, $label, $route, $httpMethod);
    }

    public function render($aData = [])
    {
        $window = [
            'window' => [
                'title' => $this->getTitulo(),
                'route' => $this->getRota()
            ]
        ];

        $component = array_merge($window, $this->getComponent()->toArray());

        echo json_encode($component);
    }

    private function atualizaValorFiltros($valores)
    {
        /** @var GridFilter $filter */
        foreach ($this->getComponent()->getFilters() as $filter) {
            foreach ($valores as $key => $value) {
                if ($filter->getName() == $key) {
                    $filter->setVaLue($value);
                }
            }
        }
    }
}
