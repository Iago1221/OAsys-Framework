<?php

namespace Framework\Interface\Domain\Log;

use Framework\Infrastructure\MVC\Model\Model;

class Log extends Model
{
    protected ?string $id;
    protected ?string $route;
    protected ?string $usuario;
    protected ?string $data;
    protected ?string $dados;

    public function __construct(?string $route = null, ?string $usuarioId = null, ?string $dados = null)
    {
        parent::__construct();
        $this->route = $route;
        $this->usuarioId = $usuarioId;
        $this->dados = $dados;
        if ($route) {
            $this->data = date('Y-m-d H:i:s');
        }
    }

    public static function comRotaUsuarioEDados($route, $usuarioId, $dados = null)
    {
        return new self($route, $usuarioId, $dados);
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getDados()
    {
        return $this->dados;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setRoute($route): void
    {
        $this->route = $route;
    }

    public function setUsuario($usuarioId): void
    {
        $this->usuario = $usuarioId;
    }

    public function setDados($dados): void
    {
        $this->dados = $dados;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }
}
