<?php

namespace Framework\Infrastructure\MVC\View\Components\ConciliacaoPanel;

class ConciliacaoPanelSide
{
    /** @var ConciliacaoPanelColumn[] */
    private array $columns = [];

    /** @var ConciliacaoPanelFilter[] */
    private array $filters = [];

    private array $rows = [];

    private array $pagination = ['page' => 1, 'limit' => 20, 'total' => 0, 'totalPages' => 1];

    public function __construct(private string $title) {}

    public function addColumn(ConciliacaoPanelColumn $column): self
    {
        $this->columns[] = $column;
        return $this;
    }

    public function addFilter(ConciliacaoPanelFilter $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function setRows(array $rows): self
    {
        $this->rows = $rows;
        return $this;
    }

    public function setPagination(int $page, int $limit, int $total): self
    {
        $totalPages = $limit > 0 ? (int) ceil($total / $limit) : 1;
        $this->pagination = [
            'page'       => $page,
            'limit'      => $limit,
            'total'      => $total,
            'totalPages' => max(1, $totalPages),
        ];
        return $this;
    }

    public function toArray(): array
    {
        return [
            'title'      => $this->title,
            'columns'    => array_map(fn ($c) => $c->toArray(), $this->columns),
            'filters'    => array_map(fn ($f) => $f->toArray(), $this->filters),
            'rows'       => $this->rows,
            'pagination' => $this->pagination,
        ];
    }
}
