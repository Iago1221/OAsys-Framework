<?php

namespace Framework\Interface\Infrastructure\Controllers\Core;

use Framework\Auth\Autenticator;
use Framework\Core\Main;
use Framework\Infrastructure\DB\Persistence\Storage\Repository\GenericRepository;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioMapper;
use Framework\Interface\Infrastructure\View\Core\LoginView;

class LoginController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            exit((new LoginView())->renderLayout());
        }

        $xUsuario = $_POST['usuario'] ?? null;
        $xSenha = $_POST['senha'] ?? null;
        $oAutenticator = new Autenticator($xUsuario, $xSenha, new UsuarioMapper(new GenericRepository(Main::getPdoStorage())));

        if ($sToken = $oAutenticator->generateToken()) {
            $_SESSION['oasys-token'] = $sToken;
            header('Location: /');
            return;
        }

        exit((new LoginView(['bUnauthorized' => true]))->renderLayout());
    }

    public function logout()
    {
        ob_clean();
        session_destroy();
        header('Location: /');
        exit();
    }
}
