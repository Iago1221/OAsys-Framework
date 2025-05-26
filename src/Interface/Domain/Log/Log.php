<?php

namespace Framework\Interface\Domain\Log;

use Framework\Infrastructure\MVC\Model\Model;

class Log extends Model
{
    protected ?string $id;
    protected ?string $route;
    protected ?string $usuarioId;
    protected ?string $data;
    protected ?string $dados;

    public function __construct(?string $route = null, ?string $usuarioId = null, ?string $dados = null)
    {
        parent::__construct();
        $this->route = $route;
        $this->usuarioId = $usuarioId;
        $this->dados = $dados;
        $this->data = date('Y-m-d H:i:s');
    }

    public static function comRotaUsuarioEDados($route, $usuarioId, $dados = null)
    {
        return new self($route, $usuarioId, $dados);
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getUsuarioId()
    {
        return $this->usuarioId;
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
}
