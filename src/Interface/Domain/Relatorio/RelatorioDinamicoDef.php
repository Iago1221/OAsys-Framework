<?php

namespace Framework\Interface\Domain\Relatorio;

use Framework\Infrastructure\MVC\Model\Model;
use Framework\Interface\Domain\Usuario\Usuario;

/**
 * Definição persistida de relatório dinâmico (estrutura JSON validada pelo framework).
 */
class RelatorioDinamicoDef extends Model
{
    private $id;
    private $nome;
    private $pacote;
    /** @var string JSON */
    private $definicao;
    private $situacao;

    /** @var Usuario|int|null FK persistida em usuario_id; após with vira {@see Usuario} */
    private $usuarioId;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function getPacote(): ?string
    {
        return $this->pacote;
    }

    public function setPacote(?string $pacote): void
    {
        $this->pacote = $pacote;
    }

    public function getDefinicao(): ?string
    {
        return $this->definicao;
    }

    public function setDefinicao(?string $definicao): void
    {
        $this->definicao = $definicao;
    }

    public function getSituacao(): ?int
    {
        return $this->situacao;
    }

    public function setSituacao(?int $situacao): void
    {
        $this->situacao = $situacao;
    }

    public function getUsuarioId(): ?int
    {
        if ($this->usuarioId === null) {
            return null;
        }
        if ($this->usuarioId instanceof Usuario) {
            return $this->usuarioId->getId();
        }

        return (int) $this->usuarioId;
    }

    public function setUsuarioId($usuarioId): void
    {
        if ($usuarioId === null || $usuarioId === '') {
            $this->usuarioId = null;

            return;
        }
        $this->usuarioId = $usuarioId instanceof Usuario ? $usuarioId : (int) $usuarioId;
    }

    /** @return Usuario|int|null */
    public function getUsuario()
    {
        return $this->usuarioId;
    }

    /** @param Usuario|int|null $usuario */
    public function setUsuario($usuario): void
    {
        $this->usuarioId = $usuario;
    }
}
