<?php

namespace Framework\Interface\Usuario;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class Usuario
{
    private ?int $iId;
    private ?string $sNome;
    private ?string $sSenha;
    private ?string $sEmail;

    public function setId(int $iId): void
    {
        if (isset($this->iId)) {
            throw new \DomainException('Não é possível alterar o ID de um usuário.');
        }

        $this->iId = $iId;
    }

    public function getId()
    {
        return $this->iId;
    }

    public function getNome(): ?string
    {
        return $this->sNome;
    }

    public function setNome(?string $sNome): void
    {
        $this->sNome = $sNome;
    }

    public function validaSenha($sSenha)
    {
        return password_verify($sSenha, $this->sSenha);
    }

    public function getSenha(): string
    {
        return $this->sSenha;
    }

    public function setSenha(?string $sSenha): void
    {
        $this->sSenha = password_hash($sSenha, PASSWORD_ARGON2ID);
    }

    public function getEmail(): ?string
    {
        return $this->sEmail;
    }

    public function setEmail(?string $sEmail): void
    {
        $this->sEmail = $sEmail;
    }
}
