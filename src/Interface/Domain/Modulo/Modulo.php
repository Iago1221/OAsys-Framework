<?php

namespace Framework\Interface\Domain\Modulo;

use Framework\Infrastructure\MVC\Model\Model;

class Modulo extends Model
{
    const SITUACAO_ATIVO = 1,
          SITUACAO_INATIVO = 2;

    CONST SISTEMA_ERP = 1,
          SISTEMA_CRM = 2,
          SISTEMA_GESTAO_ECONOMICA = 3,
          SISTEMA_VAREJO = 4,
          SISTEMA_INDUSTRIA = 5;

    protected ?int $id;
    protected ?string $titulo;
    protected ?int $situacao;
    protected ?array $itens;
    protected ?string $pacote;
    protected $sistema;

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
        return $this->itens ?? [];
    }

    public function getSituacao(): ?int
    {
        return $this->situacao;
    }

    public function isSituacao(int $situacao): bool
    {
        return $this->situacao == $situacao;
    }

    public function setPacote(string $pacote)
    {
        $this->pacote = $pacote;
    }

    public function getPacote(): string
    {
        return $this->pacote;
    }

    public function isDisponivel($usuario): bool
    {
        if ($this->getPacote() == 'Sistema' && $usuario != 1) {
            return false;
        }

        return true;
    }

    public function getSistema()
    {
        return $this->sistema;
    }

    public function setSistema($sistema)
    {
        $this->sistema = $sistema;
    }
}
