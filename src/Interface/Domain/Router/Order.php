<?php

namespace Framework\Interface\Domain\Router;

/**
 * Classe modelo de pedido gerado através de uma requisição.
 * Representa a solicitação de um processo.
 */
class Order
{
    private $route;
    private $pacote;
    private $class;
    private $method;
    private $title;

    public function __construct($route, $pacote, $class, $method, $title)
    {
        $this->route = $route;
        $this->pacote = $pacote;
        $this->class = "{$pacote}\\{$class}";
        $this->method = $method;
        $this->title = $title;
    }

    public function getPacote()
    {
        return $this->pacote;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
