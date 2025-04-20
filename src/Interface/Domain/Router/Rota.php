<?php

namespace Framework\Interface\Domain\Router;

class Rota
{
    private $iId;
    private string $sNome;
    private string $sCaminho;
    private string $sMetodo;
    private string $sPacote;
    private string $sTitulo;

    public function __construct($sNome, $sPacote, $sCaminho, $sMetodo, $sTitulo)
    {
        $this->sNome = $sNome;
        $this->sPacote = $sPacote;
        $this->sCaminho = $sCaminho;
        $this->sMetodo = $sMetodo;
        $this->sTitulo = $sTitulo;
    }

    public function getNome(): string
    {
        return $this->sNome;
    }

    public function setNome(string $sNome): void
    {
        $this->sNome = $sNome;
    }

    public function getCaminho(): string
    {
        return $this->sCaminho;
    }

    public function setCaminho(string $sCaminho): void
    {
        $this->sCaminho = $sCaminho;
    }

    public function getMetodo(): string
    {
        return $this->sMetodo;
    }

    public function setMetodo(string $sMetodo): void
    {
        $this->sMetodo = $sMetodo;
    }

    public function getTitulo(): string
    {
        return $this->sTitulo;
    }

    public function setTitulo(string $sTitulo): void
    {
        $this->sTitulo = $sTitulo;
    }

    public function getPacote(): string
    {
        return $this->sPacote;
    }

    public function setPacote(string $sPacote): void
    {
        $this->sPacote = $sPacote;
    }
}
