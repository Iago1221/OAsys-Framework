<?php

namespace Framework\Interface\Domain\Evento;

use Framework\Infrastructure\MVC\Model\Model;

class OutboxEvento extends Model
{
    protected ?int $id;
    protected string $evento;
    protected string $payload;
    protected bool $publicado;
    protected ?string $dataCriacao;
    protected ?string $dataPublicacao;

    public function __construct(?string $evento = null, array $payload = [])
    {
        parent::__construct();
        $this->evento = $evento;
        $this->payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $this->publicado = false;
        $this->dataCriacao = date('Y-m-d H:i:s');
        $this->dataPublicacao = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEvento(): string
    {
        return $this->evento;
    }

    public function setEvento(string $evento): void
    {
        $this->evento = $evento;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }

    public function getPayloadArray(): array
    {
        return json_decode($this->payload, true) ?? [];
    }

    public function isPublicado(): bool
    {
        return (bool) $this->publicado;
    }

    public function setPublicado(bool $publicado): void
    {
        $this->publicado = $publicado;
    }

    public function getDataCriacao(): ?string
    {
        return $this->dataCriacao;
    }

    public function setDataCriacao(?string $dataCriacao): void
    {
        $this->dataCriacao = $dataCriacao;
    }

    public function getDataPublicacao(): ?string
    {
        return $this->dataPublicacao;
    }

    public function setDataPublicacao(?string $dataPublicacao): void
    {
        $this->dataPublicacao = $dataPublicacao;
    }
}
