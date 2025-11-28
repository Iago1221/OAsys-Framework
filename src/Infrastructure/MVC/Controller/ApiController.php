<?php

namespace Framework\Infrastructure\MVC\Controller;

abstract class ApiController extends Controller
{
    protected $httpMethod;
    protected $method;

    protected function getViewClass(): string|null
    {
       return null;
    }

    public function setHttpMethod(string $httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }

    protected function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    abstract function execute(string $controller, string $pathParams);
}
