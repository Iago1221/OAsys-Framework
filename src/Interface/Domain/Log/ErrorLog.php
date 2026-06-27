<?php

namespace Framework\Interface\Domain\Log;

use Framework\Infrastructure\MVC\Model\Model;

class ErrorLog extends Model
{
    protected ?int $id;
    protected $usuario;
    protected $rota;
    protected ?string $data;
    protected ?string $arquivo;
    protected ?string $erro;
    protected ?string $trace;

    public function __construct(
        ?int    $usuarioId = null,
        ?int    $rotaId = null,
        ?string $arquivo = null,
        ?string $erro = null,
        ?string $trace = null
    ) {
        parent::__construct();
        $this->usuario = $usuarioId;
        $this->rota = $rotaId;
        $this->data = date('Y-m-d H:i:s');
        $this->arquivo = $arquivo;
        $this->erro = $erro;
        $this->trace = $trace;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getRota()
    {
        return $this->rota;
    }

    public function setRota($rota): void
    {
        $this->rota = $rota;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): void
    {
        $this->data = $data;
    }

    public function getArquivo(): ?string
    {
        return $this->arquivo;
    }

    public function setArquivo(?string $arquivo): void
    {
        $this->arquivo = $arquivo;
    }

    public function getErro(): ?string
    {
        return $this->erro;
    }

    public function setErro(?string $erro): void
    {
        $this->erro = $erro;
    }

    public function getTrace(): ?string
    {
        return $this->trace;
    }

    public function setTrace(?string $trace): void
    {
        $this->trace = $trace;
    }
}
