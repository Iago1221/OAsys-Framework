<?php

namespace Framework\Infrastructure;


abstract class App
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
        $this->validaRepositorio();
    }

    /** @throws \Exception */
    abstract protected function validaRepositorio(): void;

    abstract protected function getModule(): string;
    abstract protected function getModel(): string;
    public function add($dto)
    {
        return $this->repository->add(Factory::loadModel($this->getModule(), $this->getModel(), $dto));
    }

    public function remove($model)
    {
        $this->repository->remove($model);
    }

    public function update($model, $dto = null)
    {
        if ($dto) {
            Factory::setModelValues($model, $dto);
        }

        return $this->repository->update($model);
    }

    public function getAll($filters, $page, $limit)
    {
        return $this->repository->findAll($filters, $page, $limit);
    }

    public function getById($id)
    {
        return $this->repository->findById($id);
    }
}
