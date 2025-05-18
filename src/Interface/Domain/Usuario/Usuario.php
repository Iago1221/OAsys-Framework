<?php

namespace Framework\Interface\Domain\Usuario;

use Framework\Infrastructure\MVC\Model\Model;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class Usuario extends Model
{
    protected ?int $id;
    protected ?string $nome;
    protected ?string $senha;
    protected ?string $email;

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
        if ($senha && !$this->isHash($senha)) {
            $this->senha = password_hash($senha, PASSWORD_ARGON2ID);
            return;
        }

        $this->senha = $senha;
    }

    private function isHash(string $valor): bool
    {
        return str_starts_with($valor, '$argon2id$');
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
