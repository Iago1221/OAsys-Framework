<?php

namespace Framework\Infrastructure\MVC\View\Components\Grid;

use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Components\IComponent;

class Grid implements IComponent
{
    /** @var GridField[] */
    private $aColumns;
    private $aFilters;
    private $aActions;
    private $aGridActions;

    public function __construct(array $aColumns, array $aFilters, array $aActions, array $aGridActions)
    {
        $this->aColumns = $aColumns;
        $this->aFilters = $aFilters;
        $this->aActions = $aActions;
        $this->aGridActions = $aGridActions;
    }

    public function toArray($aData = []): array
    {
        $data = [
            'window' => [
                'title' => $aData['sTitle'],
                'route' => $aData['sRoute']
            ],
            'component' => 'GridComponent',
            'GridComponent' => [
                'columns' => array_map(function ($oColumn) {
                    return [
                        'field' => $oColumn->getField(),
                        'label' => $oColumn->getLabel(),
                        'type' => $oColumn->getType(),
                        'options' => $oColumn->getOptions()
                    ];
                }, $this->aColumns),
                'filters' => array_map(function ($oColumn) {
                    return [
                        'field' => $oColumn->getField(),
                        'label' => $oColumn->getLabel(),
                        'type' => $oColumn->getType(),
                        'options' => $oColumn->getOptions()
                    ];
                }, $this->aFilters),
                'actions' => $this->aActions,
                'gridActions' => $this->aGridActions,
                'rows' => $aData['aData'],
                'pagination' => [
                    'page' => $aData['aGrid']['page'],
                    'total' => $aData['aGrid']['total'],
                    'totalPages' => $aData['aGrid']['totalPages'],
                ],
            ],
        ];

        return $data;
    }
}