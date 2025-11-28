<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Interface\Domain\Router\API;
use Framework\Interface\Infrastructure\Persistence\Core\APIRepository;

abstract class ApiController extends Controller
{
    protected $httpMethod;
    protected $method;

    protected function getViewClass(): string|null
    {
       return null;
    }

    protected function getRepositoryClass(): string
    {
        return APIRepository::class;
    }

    public function setHttpMethod(string $httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }

    protected function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    abstract function getAplicacao(): string;

    /** @return API[] */
    protected function getApisFromRecurso(string $recurso): array
    {
        return $this->getRepository()->findByRecursoAndHttpMethod($this->getAplicacao(), $recurso, $this->getHttpMethod());
    }

    abstract function execute(string $recurso, string $pathParams);
}
