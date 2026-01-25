<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Modulo;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Modulo\Modulo;

class ModuloRepository extends Repository
{
    protected $filtroSistema;

    public function __construct(\PDO $pdo, $filtroSistema = true)
    {
        parent::__construct($pdo);
        $this->filtroSistema = $filtroSistema;
    }

    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function queryBuilder()
    {
        parent::queryBuilder();

        if ($this->filtroSistema) {
            $this->filterBy(['sistema' => $_SESSION['sistema']]);
        }

        $this->with(['itens']);
    }

    protected function loadItens(Modulo $modulo)
    {
        $this->hasMany($modulo, 'Itens', 'modulo', new ModuloItemRepository($this->pdo));
    }

    public function getTableName(): string
    {
        return 'modulos';
    }

    public function getModelClass(): string
    {
        return Modulo::class;
    }

    protected function setIgnorePropertys()
    {
        parent::setIgnorePropertys();
        $this->addIgonreProperty('itens');
        $this->addIgonreProperty('pacote');
    }
}