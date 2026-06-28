<?php

namespace Framework\Infrastructure\MVC\View\Components\ConciliacaoPanel;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class ConciliacaoPanel implements IComponent
{
    private ?int $conciliacaoId = null;
    private mixed $contaFinanceira = null;
    private ?float $saldoBanco = null;
    private ?float $saldoERP = null;
    private ?ConciliacaoPanelSide $leftPanel = null;
    private ?ConciliacaoPanelSide $rightPanel = null;
    private array $routes = [];

    public function getName(): string
    {
        return 'ConciliacaoPanelComponent';
    }

    public function setConciliacaoId(int $id): self
    {
        $this->conciliacaoId = $id;
        return $this;
    }

    public function setContaFinanceira(mixed $contaFinanceira): self
    {
        $this->contaFinanceira = $contaFinanceira;
        return $this;
    }

    public function setSaldoBanco(?float $saldo): self
    {
        $this->saldoBanco = $saldo;
        return $this;
    }

    public function setSaldoERP(?float $saldo): self
    {
        $this->saldoERP = $saldo;
        return $this;
    }

    public function setLeftPanel(ConciliacaoPanelSide $panel): self
    {
        $this->leftPanel = $panel;
        return $this;
    }

    public function setRightPanel(ConciliacaoPanelSide $panel): self
    {
        $this->rightPanel = $panel;
        return $this;
    }

    public function setRoutes(array $routes): self
    {
        $this->routes = $routes;
        return $this;
    }

    public function toArray(): array
    {
        $contaArr = null;
        if (is_object($this->contaFinanceira) && method_exists($this->contaFinanceira, 'getId')) {
            $contaArr = [
                'id'        => $this->contaFinanceira->getId(),
                'descricao' => method_exists($this->contaFinanceira, 'getDescricao')
                    ? $this->contaFinanceira->getDescricao()
                    : null,
            ];
        } elseif (is_array($this->contaFinanceira)) {
            $contaArr = $this->contaFinanceira;
        }

        return [
            'component'                => 'ConciliacaoPanelComponent',
            'ConciliacaoPanelComponent' => [
                'conciliacaoId'   => $this->conciliacaoId,
                'contaFinanceira' => $contaArr,
                'saldoBanco'      => $this->saldoBanco,
                'saldoERP'        => $this->saldoERP,
                'leftPanel'       => $this->leftPanel?->toArray(),
                'rightPanel'      => $this->rightPanel?->toArray(),
                'routes'          => $this->routes,
            ],
        ];
    }
}
