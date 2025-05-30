<?php

namespace Framework\Interface\Domain\Modulo;

use Framework\Infrastructure\MVC\Model\Model;
use Framework\Interface\Domain\Router\Rota;

class ModuloItem extends Model
{
    const SITUACAO_ATIVO = 1,
          SITUACAO_INATIVO = 2;

    protected $id;
    protected $rota;
    protected $titulo;
    protected $situacao;
    protected $modulo;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitulo(string $titulo)
    {
        $this->titulo = $titulo;
    }

    public function setRota(int|Rota $rota)
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

    public function getRota(): int|Rota
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