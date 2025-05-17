<?php

namespace Framework\Interface\Domain\Usuario;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class Usuario
{
    private ?int $id;
    private ?string $nome;
    private ?string $senha;
    private ?string $email;

    public function setId(int $id): void
    {
        if (isset($this->id)) {
            throw new \DomainException('Não é possível alterar o ID de um usuário.');
        }

        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function validaSenha($senha)
    {
        return password_verify($senha, $this->getSenha());
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha(?string $senha): void
    {
        $this->senha = password_hash($senha, PASSWORD_ARGON2ID);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
