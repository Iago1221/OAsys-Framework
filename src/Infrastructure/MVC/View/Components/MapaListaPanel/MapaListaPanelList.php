<?php

namespace Framework\Infrastructure\MVC\View\Components\MapaListaPanel;

class MapaListaPanelList
{
    /** @var MapaListaPanelColumn[] */
    private array $columns = [];

    /** @var MapaListaPanelFilter[] */
    private array $filters = [];

    private array $rows = [];

    public function __construct(private string $title) {}

    public function addColumn(MapaListaPanelColumn $column): self
    {
        $this->columns[] = $column;
        return $this;
    }

    public function addFilter(MapaListaPanelFilter $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function setRows(array $rows): self
    {
        $this->rows = $rows;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'title'   => $this->title,
            'columns' => array_map(fn ($c) => $c->toArray(), $this->columns),
            'filters' => array_map(fn ($f) => $f->toArray(), $this->filters),
            'rows'    => $this->rows,
        ];
    }
}
