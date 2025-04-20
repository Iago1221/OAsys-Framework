<?php

namespace Framework\Infrastructure\MVC\View\Interface;

use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Components\Grid\Grid;

abstract class GridView extends View
{
    private $aColumns = [];
    private $aFilters = [];
    private $aActions = [];
    private $aGridActions = [];

    protected function addColumn(GridField $oField, $filter = true)
    {
        $this->aColumns[] = $oField;

        if ($filter) {
            $this->addFilter($oField->getField(), $oField->getLabel(), $oField->getType());
        }
    }

    protected function addFilter($field, $label, $type)
    {
        $this->aFilters[] = ['field' => $field, 'label' => $label, 'type' => $type];
    }

    public function addAction($name, $label, $route, $httpMethod = 'GET')
    {
        $this->aActions[] = ['route' => $route, 'name' => $name, 'label' => $label, 'httpMethod' => $httpMethod];
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
        $this->aGridActions[] = ['route' => $route, 'name' => $name, 'label' => $label, 'httpMethod' => $httpMethod];
    }

    public function render($aData = [])
    {
        if ($aData['filtersValue']) {
            $this->atualizaValorFiltros((array) $aData['filtersValue']);
        }

        $oGrid = new Grid($this->aColumns, $this->aFilters, $this->aActions, $this->aGridActions);
        echo json_encode($oGrid->toArray($aData));
    }

    private function atualizaValorFiltros($valores)
    {
        $filters = [];
        foreach ($this->aFilters as $filter) {
            foreach ($valores as $key => $value) {
                if ($filter['field'] == $key) {
                    $filter['value'] = $value;
                }
            }
            $filters[] = $filter;
        }

        $this->aFilters = $filters;
    }

    public function getColumns()
    {
        return $this->aColumns;

    }
    public function getFilters() {
        return $this->aFilters;
    }
}
