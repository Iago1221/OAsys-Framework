<?php

namespace Framework\Testing;

/**
 * Repository em memória para uso em testes.
 * Substitui o acesso ao banco por um array interno, sem necessidade de PDO.
 *
 * Uso: extenda esta classe para criar um repository de teste específico.
 *
 * Exemplo:
 *   class PedidoRepositoryFake extends InMemoryRepository {}
 *
 *   $repo = new PedidoRepositoryFake();
 *   $repo->save($pedido);
 *   $this->assertSame($pedido, $repo->last());
 */
abstract class InMemoryRepository
{
    protected array $store = [];
    private int $nextId = 1;

    public function save(object $model): bool
    {
        if (!$model->getId()) {
            $model->setId($this->nextId++);
        }
        $this->store[$model->getId()] = $model;
        return true;
    }

    public function findBy(string $column, mixed $value): ?object
    {
        $getter = $this->resolveGetter($column);
        foreach ($this->store as $model) {
            if (method_exists($model, $getter) && $model->$getter() == $value) {
                return $model;
            }
        }
        return null;
    }

    public function findAllBy(string $column, mixed $value): array
    {
        $getter = $this->resolveGetter($column);
        return array_values(
            array_filter($this->store, fn($m) => method_exists($m, $getter) && $m->$getter() == $value)
        );
    }

    public function remove(object $model): bool
    {
        unset($this->store[$model->getId()]);
        return true;
    }

    public function count(): int
    {
        return count($this->store);
    }

    public function all(): array
    {
        return array_values($this->store);
    }

    public function first(): ?object
    {
        if (empty($this->store)) {
            return null;
        }
        return reset($this->store);
    }

    public function last(): ?object
    {
        if (empty($this->store)) {
            return null;
        }
        return end($this->store);
    }

    public function clear(): void
    {
        $this->store = [];
        $this->nextId = 1;
    }

    private function resolveGetter(string $column): string
    {
        $camel = lcfirst(str_replace('_', '', ucwords($column, '_')));
        return 'get' . ucfirst($camel);
    }
}
