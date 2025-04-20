<?php

namespace Framework\Infrastructure\MVC\Controller;


use Framework\Core\Main;

abstract class GridController extends Controller
{
    public function list() {
        $filters = $this->getRequest('filters') ?: [];
        $page = $this->getRequest('page') ?: 1;
        $limit = $this->getRequest('limit') ?: 10;

        $iOffset = ($page * $limit) - $limit;
        $iQtd = count($this->getMapper()->get($filters));
        $aModels = $this->getMapper()->get($filters, $limit, $iOffset);
        $aData = [];

        foreach ($aModels as $oModel) {
            $aData[] = $this->getMapper()->getAtributtesData($oModel);
        }

        if ($this->getRequest('export')) {
            $this->export($aData);
            return;
        }

        $renderData = ['aData' => $aData, 'aGrid' => $this->getGridInformations($limit, $page, $iQtd)];

        if ($filters) {
            $renderData['filtersValue'] = $filters;
        }

        $renderData['sTitle'] = Main::getOrder()->getTitle();
        $renderData['sRoute'] = Main::getOrder()->getRoute();
        $this->oView->render($renderData);
    }

    private function getGridInformations($limit, $page, $total) {
        $totalPages = intdiv($total, $limit);

        if ($total % $limit) {
            $totalPages++;
        }

        return [
          'page' => $page,
          'totalPages' => $totalPages,
          'total' => $total
        ];
    }

    public function export($data)
    {
        echo $this->getRequest('export');
    }

    public function suggestList()
    {
        $this->oView->addAction('select', 'Selecionar', '');
        $this->list();
    }

    public function suggestFind()
    {
        $iId = $this->getRequest('id');
        $oModel = $this->getMapper()->find([$this->getMapper()->getIdentifierAtributte() => $iId]);
        die(json_encode($this->getMapper()->getAtributtesData($oModel)));
    }

    public function suggestGet()
    {
        $aFilter = $this->getRequest('filter');
        $aModels = $this->getMapper()->get($aFilter);
        $aData = [];

        foreach ($aModels as $oModel) {
            $aData[] = $this->getMapper()->getAtributtesData($oModel);
        }

        die(json_encode($aData));
    }
}
