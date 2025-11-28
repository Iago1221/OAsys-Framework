<?php

namespace Framework\Interface\Domain\Router;

use Framework\Infrastructure\MVC\Model\Model;

class API extends Model
{
    protected $id;
    protected $httpMethod;
    protected $aplicacao;
    protected $recurso;
    protected $controller;
    protected $method;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    public function setHttpMethod($httpMethod)
    {
        $this->httpMethod = $httpMethod;
    }

    public function getAplicacao()
    {
        return $this->aplicacao;
    }

    public function setAplicacao($aplicacao)
    {
        $this->aplicacao = $aplicacao;
    }

    public function getRecurso()
    {
        return $this->recurso;
    }

    public function setRecurso($recurso)
    {
        $this->recurso = $recurso;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }
}
