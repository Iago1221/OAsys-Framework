<?php

namespace Framework\Infrastructure\MVC\View\Layout;

class Login implements ILayout
{
    public function render($aData = [])
    {
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <?php
        $this->renderHead();
        $this->renderBody(isset($aData['bUnauthorized']));
        ?></html><?php
    }

    public function renderHead()
    {
        ?>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login - OAsys ERP</title>
        <?php
        $this->renderStyle();
        ?></head><?php
    }

    public function renderStyle()
    {
        ?>
        <style>
            /* Reset básico */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Arial, sans-serif;
                background-color: #007bff; /* Cor de fundo azul */
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            .login-container {
                background-color: #FFF; /* Fundo branco */
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                width: 100%;
                max-width: 400px;
                text-align: center;
            }

            .login-container h2 {
                color: #007bff; /* Azul para o título */
                margin-bottom: 1.5rem;
                font-size: 1.8rem;
            }

            .login-container input {
                width: 100%;
                padding: 0.75rem;
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 1rem;
            }

            .login-container input:focus {
                border-color: #007bff; /* Azul ao focar */
                outline: none;
            }

            .login-container button {
                width: 100%;
                padding: 0.75rem;
                background-color: #007bff; /* Azul para o botão */
                color: #FFF; /* Texto branco */
                border: none;
                border-radius: 4px;
                font-size: 1rem;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .login-container button:hover {
                background-color: #0056b3; /* Azul mais escuro ao passar o mouse */
            }

            .login-container .forgot-password {
                display: block;
                margin-top: 1rem;
                color: #007bff; /* Azul para o link */
                text-decoration: none;
                font-size: 0.9rem;
            }

            .login-container .forgot-password:hover {
                text-decoration: underline;
            }
        </style>
        <?php
    }

    public function renderBody($bUnauthorized)
    {
        ?>
        <body>
            <div class="login-container">
                <?php if ($bUnauthorized): ?>
                    <h3 style="color: red">Usuário ou senha incorretos!</h3>
                <?php endif; ?>
                <h2>Login - OAsys ERP</h2>
                <form action="index.php" method="POST">
                    <input type="email" name="usuario" placeholder="Usuário" required>
                    <input type="password" name="senha" placeholder="Senha" required>
                    <button type="submit">Entrar</button>
                </form>
            </div>
        </body>
        <?php
    }
}
