<?php

namespace Framework\Interface\Domain\Relatorio;

use Framework\Infrastructure\MVC\Model\Model;

class Relatorio extends Model
{
    private $id;
    private $pacote;
    private $rota;
    private $descricao;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPacote()
    {
        return $this->pacote;
    }

    public function setPacote($pacote)
    {
        $this->pacote = $pacote;
    }

    public function getRota()
    {
        return $this->rota;
    }

    public function setRota($rota)
    {
        $this->rota = $rota;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
}
