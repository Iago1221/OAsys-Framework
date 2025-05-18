<?php

namespace Framework\Interface\Domain\Router;

use Framework\Infrastructure\MVC\Model\Model;

class Rota extends Model
{
    protected $id;
    protected ?string $nome;
    protected ?string $caminho;
    protected ?string $metodo;
    protected ?string $pacote;
    protected ?string $titulo;

    public function __construct($nome = null, $pacote = null, $caminho = null, $metodo = null, $titulo = null)
    {
        $this->nome = $nome;
        $this->pacote = $pacote;
        $this->caminho = $caminho;
        $this->metodo = $metodo;
        $this->titulo = $titulo;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getCaminho(): string
    {
        return $this->caminho;
    }

    public function setCaminho(string $caminho): void
    {
        $this->caminho = $caminho;
    }

    public function getMetodo(): string
    {
        return $this->metodo;
    }

    public function setMetodo(string $metodo): void
    {
        $this->metodo = $metodo;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getPacote(): string
    {
        return $this->pacote;
    }

    public function setPacote(string $pacote): void
    {
        $this->pacote = $pacote;
    }
}
