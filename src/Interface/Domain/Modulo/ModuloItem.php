<?php

namespace Framework\Interface\Domain\Modulo;

use Framework\Interface\Domain\Router\Rota;

class ModuloItem
{
    const SITUACAO_ATIVO = 1,
          SITUACAO_INATIVO = 2;

    private $id;
    private $rota;
    private $titulo;
    private $situacao;
    private $modulo;

    public function setTitulo(string $titulo)
    {
        $this->titulo = $titulo;
    }

    public function setRota(Rota $rota)
    {
        $this->rota = $rota;
    }

    public function setSituacao(int $situacao)
    {
        $this->situacao = $situacao;
    }

    public  function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getRota(): Rota
    {
        return $this->rota;
    }

    public function getSituacao(): int
    {
        return $this->situacao;
    }

    public function isSituacao(int $situacao): bool
    {
        return $this->situacao == $situacao;
    }

    public function setModulo(int $modulo)
    {
        $this->modulo = $modulo;
    }

    public function getModulo(): int
    {
        return $this->modulo;
    }
}