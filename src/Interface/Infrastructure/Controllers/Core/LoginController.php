<?php

namespace Framework\Interface\Infrastructure\Controllers\Core;

use Framework\Auth\Autenticator;
use Framework\Core\Main;
use Framework\Infrastructure\MVC\Controller\Controller;
use Framework\Infrastructure\Response;
use Framework\Interface\Domain\Modulo\Modulo;
use Framework\Interface\Domain\Usuario\Usuario;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;
use Framework\Interface\Infrastructure\View\Core\LoginView;

class LoginController extends Controller
{
    protected function getViewClass(): string|null
    {
        return null;
    }

    protected function getRepositoryClass(): string
    {
        return UsuarioRepository::class;
    }

    public function loginApi()
    {
        $xUsuario = $this->getRequest('usuario');
        $xSenha = $this->getRequest('senha');

        if (!$xUsuario || !$xSenha) {
            Response::error('Unauthorized', 401);
        }

        $usuarioRepository = new UsuarioRepository(Main::getConnection());
        $oAutenticator = new Autenticator($xUsuario, $xSenha, $usuarioRepository);


        if ($sToken = $oAutenticator->generateToken()) {
            Response::success(['token' => $sToken]);
            return;
        }

        Response::error("Unauthorized", 401);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            exit((new LoginView())->renderLayout());
        }

        $xUsuario = $_POST['usuario'] ?? null;
        $xSenha = $_POST['senha'] ?? null;

        if (!$xUsuario || !$xSenha) {
            Response::error('Unauthorized', 401);
        }

        $usuarioRepository = new UsuarioRepository(Main::getConnection());
        $oAutenticator = new Autenticator($xUsuario, $xSenha, $usuarioRepository);

        if ($sToken = $oAutenticator->generateToken()) {
            $_SESSION['oasys-token'] = $sToken;

            /** @var Usuario $usuario */
            $usuario = $usuarioRepository->findBy('email', $xUsuario);
            $_SESSION['usuario'] = $usuario->getId();

            $_SESSION['sistema'] = Modulo::SISTEMA_ERP;

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
        unset($_SESSION['oasys-token']);
        header('Location: /' . $_SESSION['cliente']);
        exit();
    }
}
