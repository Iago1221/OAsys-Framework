<?php

namespace Framework\Interface\Infrastructure\Persistence\Core;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Router\API;

class APIRepository extends Repository
{
    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function getTableName(): string
    {
        return 'api';
    }

    /** @return API[] */
    public function findByRecursoAndHttpMethod($aplicacao, $recurso, $httpMethod)
    {
        $this->filterBy(['aplicacao' => $aplicacao, 'recurso' => $recurso, 'httpMethod' => $httpMethod]);
        return $this->get();
    }

    public function getModelClass(): string
    {
        return API::class;
    }
}