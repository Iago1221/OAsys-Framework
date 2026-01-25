<?php

namespace Framework\Interface\Domain\Usuario;

use Framework\Infrastructure\MVC\Model\Model;

class UsuarioModuloItem extends Model
{
    protected $id;
    protected $usuario;
    protected $moduloItem;
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

    public function getModuloItem()
    {
        return $this->moduloItem;
    }

    public function setModuloItem($moduloItem)
    {
        $this->moduloItem = $moduloItem;
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
