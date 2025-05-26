<?php

namespace Framework\Interface\Domain\Modulo;

use Framework\Infrastructure\MVC\Model\Model;

class Modulo extends Model
{
    const SITUACAO_ATIVO = 1,
          SITUACAO_INATIVO = 2;

    protected ?int $id;
    protected ?string $titulo;
    protected ?int $situacao;
    protected ?array $itens;
    protected ?string $pacote;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitulo(string $sTitulo)
    {
        $this->titulo = $sTitulo;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setSituacao(?int $situacao)
    {
        $this->situacao = $situacao;
    }

    public function setItens(array $itens) {
        foreach ($itens as $item) {
            $this->addItem($item);
        }
    }

    public function addItem($item)
    {
        $this->itens[] = $item;
    }

    /** @return ModuloItem[] */
    public function getItens(): array
    {
        return $this->itens;
    }

    public function getSituacao(): ?int
    {
        return $this->situacao;
    }

    public function isSituacao(int $situacao): bool
    {
        return $this->situacao == $situacao;
    }
}