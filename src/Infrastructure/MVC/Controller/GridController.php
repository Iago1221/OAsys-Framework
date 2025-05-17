<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Interface\GridView;

abstract class GridController extends Controller
{
    protected GridView $view;
    private array $filtros;
    private int $pagina;
    private int $limite;
    private int $quantidadeRegistros;
    private array $registros;

    protected function setAtributosFromRequest() {
        $this->setFiltros($this->getRequest('filters') ?: []);
        $this->setPagina($this->getRequest('page') ?: 1);
        $this->setLimite($this->getRequest('limit') ?: 10);
        $this->setQuantidadeRegistros();
        $this->setRegistros();
    }

    protected function setFiltros(array $aFiltros): void {
        $this->filtros = $aFiltros;
    }

    protected function setPagina($iPagina) {
        $this->pagina = $iPagina;
    }

    protected function setLimite($iLimite) {
        $this->limite = $iLimite;
    }

    protected function setQuantidadeRegistros() {
        $this->getRepository()->filterBy($this->getFiltros());
        $this->quantidadeRegistros = $this->getRepository()->count();
    }

    protected function setRegistros() {
        $this->getRepository()->filterBy($this->getFiltros());
        $this->registros = $this->getRepository()->paginate($this->getLimite(), $this->getPagina());
    }

    protected function getFiltros() {
        return $this->filtros;
    }

    protected function getPagina() {
        return $this->pagina;
    }

    protected function getLimite() {
        return $this->limite;
    }

    protected function getQuantidadeRegistros() {
        return $this->quantidadeRegistros;
    }

    protected function getRegistros() {
        return $this->registros;
    }

    public function bean()
    {
        $data = [];

        foreach ($this->getRegistros() as $registro) {
            if (is_object($registro)) {
                $data[] = $this->mapModelToArray($registro);
                continue;
            }

            $data[] = $registro;
        }

        return $data;
    }

    public function list() {
        $this->setAtributosFromRequest();
        $data = $this->bean();

        if ($this->getRequest('exportar')) {
            $this->export($data);
            return;
        }

        $this->beforeBindView();
        $this->getView()->getComponent()->setRows($data);
        $this->getView()->getComponent()->setInformacoes($this->getGridInformations($this->getLimite(), $this->getPagina(), $this->getQuantidadeRegistros()));
        $this->getView()->getComponent()->setValorFiltros($this->getFiltros());
        $this->getView()->setTitulo(Main::getOrder()->getTitle());
        $this->getView()->setRota(Main::getOrder()->getRoute());

        $this->beforeRender();
        $this->getView()->render();
    }

    protected function beforeBindView() {}
    protected function beforeRender() {}

    protected function getGridInformations($limit, $page, $total) {
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

    public function export($aData)
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="exportacao.csv"');

        $out = fopen('php://output', 'w');

        if (count($aData) > 0) {
            $aKeys = array_keys($aData[0]);
            fputcsv($out, $aKeys); // Cabeçalhos

            foreach ($aData as $aLinha) {
                $aPut = [];
                foreach ($aKeys as $sKey) {
                    if (is_array($aLinha[$sKey])) {
                        $sLinha = '';
                        $i = 0;
                        $j = count($aLinha);
                        foreach ($aLinha[$sKey] as $sTextoLinha) {
                            if ($sTextoLinha) {
                                $sLinha .= $sTextoLinha;

                                if ($i < $j) {
                                    $sLinha .= ' - ';
                                }
                            }
                        }

                        $aPut[] = $sLinha;
                        continue;
                    }

                    $aPut[] = $aLinha[$sKey];
                }

                fputcsv($out, $aPut);
            }

            fclose($out);
        }

        exit;
    }

    public function suggestList()
    {
        $this->getView()->addAction('select', 'Selecionar', '');
        $this->list();
    }

    public function suggestFind()
    {
        $iId = $this->getRequest('id');
        $oModel = $this->getRepository()->findBy('id', $iId);
        die(json_encode($this->mapModelToArray($oModel)));
    }

    public function suggestGet()
    {
        $aFilter = $this->getRequest('filter');
        $aFilter = [$aFilter[0], 'LIKE', $aFilter[1]];
        $this->getRepository()->filterBy($aFilter);

        $aModels = $this->getRepository()->get();
        $aData = [];

        foreach ($aModels as $oModel) {
            $aData[] = $this->mapModelToArray($oModel);
        }

        die(json_encode($aData));
    }
}
