<?php

namespace Framework\Interface\Infrastructure\Controllers\Core;

use Framework\Auth\Autenticator;
use Framework\Core\Main;
use Framework\Interface\Domain\Modulo\Modulo;
use Framework\Interface\Domain\Usuario\Usuario;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;
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
        $usuarioRepository = new UsuarioRepository(Main::getConnection());
        $oAutenticator = new Autenticator($xUsuario, $xSenha, $usuarioRepository);

        if ($sToken = $oAutenticator->generateToken()) {
            $_SESSION['oasys-token'] = $sToken;

            /** @var Usuario $usuario */
            $usuario = $usuarioRepository->findBy('email', $xUsuario);
            $_SESSION['usuario'] = $usuario->getId();

            if ($usuario->getAcessoCrm()) {
                $_SESSION['sistema'] = Modulo::SISTEMA_CRM;
            }

            if ($usuario->getAcessoErp()) {
                $_SESSION['sistema'] = Modulo::SISTEMA_ERP;
            }

            header('Location: /');
            return;
        }

        exit((new LoginView(['bUnauthorized' => true]))->renderLayout());
    }

    public function logout()
    {
        ob_clean();
        session_destroy();
        header('Location: /' . $_SESSION['cliente']);
        exit();
    }
}
