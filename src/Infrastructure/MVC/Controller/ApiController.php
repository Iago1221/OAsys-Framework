<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Infrastructure\Factory;
use Framework\Interface\Domain\Router\API;
use Framework\Interface\Infrastructure\Persistence\Core\APIRepository;

abstract class ApiController extends Controller
{
    protected $httpMethod;
    protected $method;
    protected $publicRecursos = [];

    protected function getViewClass(): string|null
    {
       return null;
    }

    protected function getRepositoryClass(): string
    {
        return APIRepository::class;
    }

    protected function addPublicRecurso($recurso)
    {
        $this->publicRecursos[] = $recurso;
    }

    public function recursoIsPublic($recurso)
    {
        return in_array($recurso, $this->publicRecursos);
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

    protected function loadController(string $controllerClass): Controller
    {
        $controllerClass = 'API' . '\\' . $this->getAplicacao() . '\\' . $controllerClass;
        return Factory::loadController('API', $controllerClass);
    }

    abstract function execute(string $recurso, array $pathParams);
}
