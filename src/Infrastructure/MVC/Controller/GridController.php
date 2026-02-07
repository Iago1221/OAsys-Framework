<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Interface\GridView;

abstract class GridController extends Controller
{
    protected GridView $view;
    private array $filtros;
    private ?int $pagina;
    private ?int $limite;
    private int $quantidadeRegistros;
    private array $registros;

    protected function setAtributosFromRequest() {
        $this->setFiltros($this->getRequest('filters') ?: []);
        $this->setPagina($this->getRequest('page') ?: 1);
        $this->setLimite($this->getRequest('limit') ?: 10);
        $this->setQuantidadeRegistros();
        $this->setRegistros();
    }

    protected function setFiltros(array $filtros): void {
        $this->filtros = $filtros;
        $this->trataFiltros($this->filtros);
    }

    protected function trataFiltros(&$filtros) {}

    protected function setPagina($iPagina) {
        $this->pagina = null;

        if (!$this->getRequest('exportar')) {
            $this->pagina = $iPagina;
        }
    }

    protected function setLimite($iLimite) {
        $this->limite = null;

        if (!$this->getRequest('exportar')) {
            $this->limite = $iLimite;
        }
    }

    protected function setQuantidadeRegistros() {
        $this->getRepository()->filterBy($this->getFiltros());
        $this->quantidadeRegistros = $this->getRepository()->count();
    }

    protected function setRegistros() {
        $this->beforeSetRegistros();
        $this->getRepository()->filterBy($this->getFiltros());

        if (!$this->getRequest('exportar')) {
            $totalPages = intdiv($this->getQuantidadeRegistros(), $this->getLimite());

            if ($totalPages <= 1) {
                $totalPages = 1;
            }

            if ($this->getQuantidadeRegistros() % $this->getLimite()) {
                $totalPages++;
            }

            if ($this->getPagina() > $totalPages) {
                $this->setPagina($totalPages);
            }
        }

        if ($this->getLimite()) {
            $this->registros = $this->getRepository()->paginate($this->getLimite(), $this->getPagina());
            return;
        }

        $this->registros = $this->getRepository()->get();
    }

    protected function beforeSetRegistros() {
        $this->getRepository()->orderBy('id', 'DESC');
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
            $this->beforeBeanRegistro($registro);
            if (is_object($registro)) {
                $data[] = $this->mapModelToArray($registro);
                continue;
            }

            $data[] = $registro;
        }

        return $data;
    }

    protected function beforeBeanRegistro(&$registro) {}

    protected function mergeFixedFilters(array $requestFilters): array
    {
        $grid = $this->getView()->getViewComponent();
        $fixedFilters = $grid->getFixedFilters();

        $merged = [];
        $usedRequestIndexes = [];

        foreach ($fixedFilters as $fixedIndex => $fixed) {
            $applied = false;

            foreach ($requestFilters as $i => $req) {
                if (
                    !$applied &&
                    ($req['name'] ?? null) === $fixed['name'] &&
                    ($req['operator'] ?? null) === $fixed['operator']
                ) {
                    // Aplica o valor do request no filtro fixo
                    $merged[] = array_merge($fixed, [
                        'value' => $req['value']
                    ]);

                    $usedRequestIndexes[] = $i;
                    $applied = true;
                    break;
                }
            }

            if (!$applied) {
                // Mantém filtro fixo original
                $merged[] = $fixed;
            }
        }

        // Adiciona filtros do usuário que NÃO foram usados
        foreach ($requestFilters as $i => $req) {
            if (!in_array($i, $usedRequestIndexes, true)) {
                $merged[] = $req;
            }
        }

        return $merged;
    }


    public function list() {
        $this->setAtributosFromRequest();
        $data = $this->bean();

        if ($this->getRequest('exportar')) {
            $this->export($data);
            return;
        }

        $this->beforeBindView();
        $this->getView()->getViewComponent()->setRows($data);
        $this->getView()->getViewComponent()->setInformacoes($this->getGridInformations($this->getLimite(), $this->getPagina(), $this->getQuantidadeRegistros()));
        $filtersRows = $this->mergeFixedFilters($this->getFiltros());
        $this->getView()->getViewComponent()->setFiltersRows($filtersRows);
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
            $aKeys = [];
            $aCabecalho = [];
            $columns = $this->getView()->getViewComponent()->getColumns();

            /** @var GridField $column */
            foreach ($columns as $column) {
                $aKeys[] = $column->getName();
                $aCabecalho[] = $column->getLabel();
            }

            fputcsv($out, $aCabecalho);

            foreach ($aData as $aLinha) {
                $aPut = [];
                foreach ($aKeys as $sKey) {
                    $aSplitKeys = explode('/', $sKey);
                    if (is_array($aLinha[$aSplitKeys[0]])) {
                        $aPut[] = $aLinha[$aSplitKeys[0]][$aSplitKeys[1]];
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
        $this->getView()->getViewComponent()->resetActions();
        $this->getView()->getViewComponent()->resetGridActions();
        $this->getView()->addAction('select', 'Selecionar', '');
        $this->list();
    }

    public function suggestFind()
    {
        $id = $this->getRequest('id');
        $model = $this->getRepository()->findBy('id', $id);
        die(json_encode($model && is_object($model) ? $this->mapModelToArray($model) : null));
    }

    public function suggestGet()
    {
        $filter = $this->getRequest('filter');
        $keys = array_keys($filter);

        $by = [];
        $by[] = ['name' => $keys[0], 'operator' => 'CONTEM', 'value' => $filter[$keys[0]]];
        $this->trataFiltros($by);
        $this->getRepository()->filterBy($by);

        $models = $this->getRepository()->get();
        $data = [];

        foreach ($models as $model) {
            if (is_object($model)) {
                $data[] = $this->mapModelToArray($model);
            }
        }

        die(json_encode($data));
    }
}
