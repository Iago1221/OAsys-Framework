<?php

namespace Framework\Interface\Domain\Modulo;

use Framework\Interface\Domain\Router\Rota;

class ModuloItem
{
    const SITUACAO_ATIVO = 1,
          SITUACAO_INATIVO = 2;

    private $iId;
    private $oRota;
    private $sTitulo;
    private $iSituacao;
    private $iModulo;

    public function __construct($oRota, $sTitulo)
    {
        $this->oRota = $oRota;
        $this->sTitulo = $sTitulo;
    }

    public function setTitulo(string $sTitulo)
    {
        $this->sTitulo = $sTitulo;
    }

    public function setRota(Rota $oRota)
    {
        $this->oRota = $oRota;
    }

    public function setSituacao(int $iSituacao)
    {
        $this->iSituacao = $iSituacao;
    }

    public  function getTitulo(): string
    {
        return $this->sTitulo;
    }

    public function getRota(): Rota
    {
        return $this->oRota;
    }

    public function getSituacao(): int
    {
        return $this->iSituacao;
    }

    public function isSituacao(int $iSituacao): bool
    {
        return $this->iSituacao == $iSituacao;
    }

    public function setModulo(int $iModulo)
    {
        $this->iModulo = $iModulo;
    }

    public function getModulo(): int
    {
        return $this->iModulo;
    }
}