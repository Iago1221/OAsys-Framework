<?php

namespace Framework\Interface\Domain\Router;

/**
 * Classe modelo de pedido gerado através de uma requisição.
 * Representa a solicitação de um processo.
 */
class Order
{
    private $route;
    private $class;
    private $method;
    private $title;

    public function __construct($route, $class, $method, $title)
    {
        $this->route = $route;
        $this->class = $class;
        $this->method = $method;
        $this->title = $title;
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
