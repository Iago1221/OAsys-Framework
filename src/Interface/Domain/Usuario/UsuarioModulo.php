<?php

namespace Framework\Interface\Domain\Usuario;

use Framework\Infrastructure\MVC\Model\Model;

class UsuarioModulo extends Model
{
    protected $id;
    protected $usuario;
    protected $modulo;
    protected $permitido;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getModulo()
    {
        return $this->modulo;
    }

    public function setModulo($modulo)
    {
        $this->modulo = $modulo;
    }

    public function getPermitido()
    {
        return $this->permitido;
    }

    public function setPermitido($permitido)
    {
        $this->permitido = $permitido;
    }
}
