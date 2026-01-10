<?php

namespace Framework\Interface\Domain\Sistema;

use Framework\Infrastructure\MVC\Model\Model;

class Sistema extends Model
{
    protected $id;
    protected $descricao;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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
