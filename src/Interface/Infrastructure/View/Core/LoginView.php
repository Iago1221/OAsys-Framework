<?php

namespace Framework\Interface\Infrastructure\View\Core;

use Framework\Infrastructure\MVC\View\Interface\View;
use Framework\Infrastructure\MVC\View\Layout\Login;

class LoginView extends View
{
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->setLayout(new Login());
    }

    protected function create()
    {

    }

    public function render()
    {
        throw new \DomainException('Não se pode renderizar componentes na raiz!');
    }
}
