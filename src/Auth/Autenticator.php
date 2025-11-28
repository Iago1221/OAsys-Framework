<?php

namespace Framework\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Framework\Interface\Domain\Usuario\Usuario;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

/**
 * Classe que gerencia a autenticação.
 * Realiza o login e valida a integridade do token de autenticação.
 *
 * @since 2024
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class Autenticator
{
    private string $login;
    private string $password;
    private string $secretKey;
    private UsuarioRepository $repository;

    public function __construct(string $login, string $password, UsuarioRepository $respository)
    {
        $this->login = $login;
        $this->password = $password;
        $this->secretKey = General::$SECRET_JWT;
        $this->repository = $respository;
    }

    /**
     * Executa o processo de validação do login.
     * @return bool
     */
    public function login(): bool
    {
        /** @var Usuario $oUsuario */
        $oUsuario = $this->repository->findBy('email', $this->login);

        if (!$oUsuario) return false;

        return (password_verify($this->password, $oUsuario->getSenha()));
    }

    /**
     * Gera um token de acesso.
     * @return false|string
     */
    public function generateToken()
    {
        if(!$this->login()) return false;

        $dadosToken = [
            "usuario" => $this->login,
            "tempo_de_expiracao" => time() + 14400
        ];
        $token = JWT::encode($dadosToken, $this->secretKey, 'HS256');
        return $token;
    }

    /**
     * Executa o processo de validação da autenticação após o login.
     * @return bool
     */
    public static function verifyToken()
    {
        $token = self::getTokenFromSession();

        if (!$token) return false;

        if (self::validateToken($token)) return true;

        return false;
    }

    /**
     * Executa o processo de validação da autenticação na API
     * @return bool
     */
    public static function verifyApiToken()
    {
        $token = self::getTokenFromHeaders();

        if (!$token) return false;

        if (self::validateToken($token)) return true;

        return false;
    }

    /**
     * Obtem o token da variavel de ambiente '$_SESSION'.
     * @return mixed|null
     */
    private static function getTokenFromSession()
    {
        if (isset($_SESSION['oasys-token'])) {
            return $_SESSION['oasys-token'];
        }

        return null;
    }

    /**
     * Obtem o token da variavel de ambiente dos Headers HTTP
     * @return mixed|null
     */
    private static function getTokenFromHeaders()
    {
        $headers = getallheaders();
        if (isset($headers['oasys-token'])) {
            $token = $headers['oasys-token'];
            return $token;
        }
        return null;
    }


    /**
     * Verifica se o token passado por parâmetro é valido.
     * @param $token
     * @return bool
     */
    private static function validateToken($token)
    {
        $jwtKey = new Key(General::$SECRET_JWT, 'HS256');
        $options = new \stdClass();
        $options->algorithm = 'HS256';

        try {
            $decoded = \Firebase\JWT\JWT::decode($token, $jwtKey, $options);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
