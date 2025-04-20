<?php

namespace Framework\Interface\Domain\Modulo;

class Modulo
{
    const SITUACAO_ATIVO = 1,
          SITUACAO_INATIVO = 2;

    private $iId;
    private $sTitulo;
    private $iSituacao;
    private $aItens;
    private $sPacote;

    public function __construct($sTitulo)
    {
        $this->sTitulo = $sTitulo;
    }

    public function getId()
    {
        return $this->iId;
    }

    public function setTitulo(string $sTitulo)
    {
        $this->sTitulo = $sTitulo;
    }

    public function getTitulo(): string
    {
        return $this->sTitulo;
    }

    public function setSituacao(int $sSituacao)
    {
        $this->iSituacao = $sSituacao;
    }

    public function setItens(array $aItens) {
        foreach ($aItens as $oItem) {
            $this->addItem($oItem);
        }
    }

    public function addItem($oItem)
    {
        $this->aItens[] = $oItem;
    }

    /** @return ModuloItem[] */
    public function getItens(): array
    {
        return $this->aItens;
    }

    public function isSituacao(int $iSituacao): bool
    {
        return $this->iSituacao == $iSituacao;
    }
}