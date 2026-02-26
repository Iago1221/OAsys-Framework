<?php

namespace Framework\Infrastructure\MVC\View\Components\Grid;

use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Components\Fields\GridFilter;
use Framework\Infrastructure\MVC\View\Components\IComponent;

class Grid implements IComponent
{
    /** @var GridField[] */
    private array $columns = [];

    /** @var GridFilter[] */
    private array $filters;
    private array $filtersRows = [];
    private array $actions = [];
    private array $gridActions = [];
    private array $rows = [];
    private array $informacoes = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setRows(array $rows)
    {
        $this->rows = $rows;
    }

    public function setFiltersRows(array $filtros)
    {
        $this->filtersRows = [];

        foreach ($filtros as $filtro) {
            $this->filtersRows[] = $filtro;
        }
    }

    public function getFixedFilters(): array
    {
        return array_filter($this->filtersRows, fn ($f) => ($f['fixed'] ?? false) === true);
    }

    public function addFixedFilter(string $name, ?string $operator, $value = null, bool $removable = false)
    {
        $this->filtersRows[] = [
            'name' => $name,
            'operator' => $operator,
            'value' => $value,
            'fixed' => true,
            'removable' => $removable
        ];
    }

    public function getFiltersRows()
    {
        return $this->filtersRows;
    }

    public function getFilter($name)
    {
        return $this->filters[$name];
    }

    public function setInformacoes(array $aInformacoes)
    {
        $this->informacoes = $aInformacoes;
    }

    public function addColumn(GridField $column)
    {
        $this->columns[$column->getName()] = $column;
    }

    public function addFilter(GridFilter $filter)
    {
        $this->filters[$filter->getName()] = $filter;
    }

    public function addAction($name, $label, $route, $httpMethod = 'GET', $blank = false, $icon = null, $color = null)
    {
        $this->actions[] = ['route' => $route, 'name' => $name, 'label' => $label, 'httpMethod' => $httpMethod, 'blank' => $blank, 'icon' => $icon, 'color' => $color];
    }

    public function addGridAction($name, $label, $route, $httpMethod = 'GET', $icon = null)
    {
        $this->gridActions[] = ['route' => $route, 'name' => $name, 'label' => $label, 'httpMethod' => $httpMethod, 'icon' => $icon];
    }

    public function getGridActions()
    {
        return $this->gridActions;
    }

    public function getInformacao($name)
    {
        return $this->informacoes[$name];
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function resetActions()
    {
        $this->actions = [];
    }

    public function resetGridActions()
    {
        $this->gridActions = [];
    }

    public function toArray(): array
    {
        $data = [
            'component' => 'GridComponent',
            'GridComponent' => [
                'columns' => array_values(array_map(function ($oColumn) {
                    return [
                        'name' => $oColumn->getName(),
                        'label' => $oColumn->getLabel(),
                        'type' => $oColumn->getType(),
                        'options' => $oColumn->getOptions()
                    ];
                }, $this->getColumns())),
                'filters' => array_values(array_map(function ($filter) {
                    return [
                        'name' => $filter->getName(),
                        'label' => $filter->getLabel(),
                        'type' => $filter->getType(),
                        'options' => $filter->getOptions(),
                        'value' => $filter->getValue(),
                        'operator' => $filter->getOperator()
                    ];
                }, $this->getFilters())),
                'filtersRows' => $this->getFiltersRows(),
                'actions' => $this->getActions(),
                'gridActions' => $this->getGridActions(),
                'rows' => $this->getRows(),
                'pagination' => [
                    'page' => $this->getInformacao('page'),
                    'total' => $this->getInformacao('total'),
                    'totalPages' => $this->getInformacao('totalPages'),
                ],
            ],
        ];

        return $data;
    }
}