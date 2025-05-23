<?php

namespace Framework\Infrastructure\DB\Persistence\Repository;

use Throwable;

/**
 * Classe abstrata de Repositórios.
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 * @since 16/05/2025
 */
abstract class Repository {
    protected \PDO $pdo;
    protected string $modelClass;
    protected string $table;
    protected array $filters = [];
    protected array $bindings = [];
    protected array $with = [];
    protected ?string $orderBy = null;
    protected ?string $orderDir = null;
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $joins = [];
    protected bool $controlaTransacao = true;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->setModelInfo();
    }

    /**
     * Retorna a classe de domínio a ser manipulada pelo repositório.
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     * Retorna o schema do banco de dados em que a tabela a ser persisida pelo repositório está.
     * @return string|null
     */
    abstract protected function getSchema(): ?string;

    /**
     * Retorna a tabela do bando de dados a ser persisitda pelo repositório.
     * @return string
     */
    abstract protected function getTableName(): string;

    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Inicia os atributos de nome da classe e da tabela de domínio.
     * @return void
     * @throws \ReflectionException
     */
    protected function setModelInfo(): void
    {
        $this->modelClass = $this->getModelClass();
        $this->table = $this->getSchema() ? "{$this->getSchema()}.{$this->getTableName()}" : $this->getTableName();
    }

    /**
     * Adiciona um Join na consulta a ser realizada pelo repositório.
     * @param string $table - Tabela a fazer a junção
     * @param string $localColumn - Coluna da tabela de domínio a ser utilizada na junção
     * @param string $foreignColumn - Coluna na tabela estrangeia a ser utilizada na junção
     * @param string $type - Tipo da junção
     * @param string $alias - Alias da tabela estrangeia
     * @param boolean $lateral - Define se é uma junção lateral
     * @return $this
     */
    public function addJoin(string $table, string $localColumn, string $foreignColumn, string $type = 'INNER', string $alias = null, bool $lateral = false): self
    {
        $join = $lateral ? 'JOIN LATERAL' : 'JOIN';
        $alias = $alias ?: $table;
        $this->joins[] = strtoupper($type) . " $join $table AS $alias ON {$this->table}.$localColumn = $alias.$foreignColumn";
        return $this;
    }

    /**
     * Define os filtros a serem realizados pela consulta do repositório.
     * @param array $conditions
     * @return $this
     */
    public function filterBy(array $conditions): self
    {
        foreach ($conditions as $key => $condition) {
            if (is_array($condition)) {
                ['name' => $column, 'operator' => $operator, 'value' => $value] = $condition;
                $this->filters[] = "$column {$this->translateOperator($operator)} ?";
                $this->bindings[] = $this->trataFiltroValue($value, $operator);
            } else {
                $this->filters[] = "$key = ?";
                $this->bindings[] = $condition;
            }
        }

        return $this;
    }

    private function trataFiltroValue($value, $operator)
    {
        if (strtoupper($operator) == 'CONTEM') {
            $value = "%{$value}%";
        }

        return $value;
    }

    private function translateOperator($operator)
    {
        $operators = [
            'IGUAL' => '=',
            'DIFERENTE' => '<>',
            'CONTEM' => 'LIKE',
            'MAIOR' => '>',
            'MENOR' => '<',
            'MAIOR IGUAL' => '>=',
            'MENOR IGUAL' => '<='
        ];

        return $operators[strtoupper($operator)];
    }

    /**
     * Define a ordem a ser realizada pela consulta  do repositório.
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBy = $column;
        $this->orderDir = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        return $this;
    }

    /**
     * Define relações com outros modelos.
     * É necessário criar o metodo 'load{NomeModelo}' que será chamado automaticamente para cada modelo instanciado.
     * @see loadRelation
     * @param string[] $relations - Array de modelos a serem relacionados.
     * @return $this
     */
    public function with(array $relations): self
    {
        $this->with = $relations;
        return $this;
    }

    /**
     * Define o limit da consulta.
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Define o offset da consulta.
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Realiza a paginação da consulta.
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function paginate(int $perPage, int $page = 1): array
    {
        $this->limit($perPage)->offset(($page - 1) * $perPage);
        return $this->get();
    }

    /**
     * Monta a consulta e realiza a busca dos registros.
     * @return array
     */
    public function get(): array
    {
        $this->queryBuilder();
        $sql = "SELECT {$this->table}.* FROM {$this->table}";

        if ($this->joins) {
            $sql .= " " . implode(" ", $this->joins);
        }
        if ($this->filters) {
            $sql .= " WHERE " . implode(" AND ", $this->filters);
        }
        if ($this->orderBy) {
            $sql .= " ORDER BY {$this->orderBy} {$this->orderDir}";
        }
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }
        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $models = array_map([$this, 'mapToModel'], $results);
        foreach ($models as $model) {
            foreach ($this->with as $relation) {
                $this->loadRelation($model, $relation);
            }
        }

        $this->resetQuery(); // importante para reuso

        return $models;
    }

    /**
     * Metodo deve ser utilizado para criar a query a ser utilizada nas consultas pelo repositório.
     * Antes de cada consulta, este metodo será chamado.
     * @return void
     */
    protected  function queryBuilder() {}

    /**
     * Conta os registros de acordo com os filtros adicionados.
     * @return int
     */
    public function count(): int
    {
        $this->queryBuilder();
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";

        if ($this->joins) {
            $sql .= " " . implode(" ", $this->joins);
        }

        if ($this->filters) {
            $sql .= " WHERE " . implode(" AND ", $this->filters);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->resetQuery();

        return $result['total'] ?? 0;
    }

    /**
     * Reseta as informações da consulta no repositório.
     * É chamado automaticamente a cada consulta ao banco.
     * @return void
     */
    protected function resetQuery(): void
    {
        $this->filters = [];
        $this->bindings = [];
        $this->joins = [];
        $this->with = [];
        $this->orderBy = null;
        $this->orderDir = null;
        $this->limit = null;
        $this->offset = null;
    }

    /**
     * Mapeia o array de parâmetro para uma instância do modelo de domínio.
     * @param array $data
     * @return mixed
     */
    public function mapToModel(array $data): object
    {
        $model = new $this->modelClass();
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($model, $method)) {
                $model->$method($value);
            }
        }

        return $model;
    }

    /**
     * Realiza a chamada dos métodos de relação definidos no metodo 'with'.
     * @see with
     * @param $model
     * @param string $relationName
     * @return void
     */
    protected function loadRelation($model, string $relationName): void
    {
        $method = 'load' . ucfirst($relationName);
        if (method_exists($this, $method)) {
            $this->$method($model);
        }
    }

    // ---------- RELACIONAMENTOS ----------

    /**
     * Define que o modelo possui um relacionamento de 1 para outro modelo.
     * @param $model - Modelo
     * @param string $relatedClass Nome do atributo que referencia a classe relacionada.
     * @param string $foreignKey - FK que representa o modelo na tabela relacionada.
     * @param Repository $relatedRepository - Repositório da classe que contém o modelo.
     * @param string $localKey - Nome do atributo que representa o relacionamento no modelo.
     * @return void
     */
    protected function hasOne($model, string $relatedClass, string $foreignKey, Repository $relatedRepository, string $localKey = 'id')
    {
        $localKeyValue = $model->{'get' . ucfirst($localKey)}();

        if (is_object($localKeyValue)) {
            $localKeyValue = $localKeyValue->{'get' . ucfirst($foreignKey)}();
        }

        $relatedModel = $relatedRepository->findBy($foreignKey, $localKeyValue);
        $model->{'set' . ucfirst($relatedClass)}($relatedModel);
    }

    /**
     * Define que o modelo possui um relacionamento de muitos para outro modelo.
     * @param $model - Modelo
     * @param string $relatedClass - Nome do atributo que referencia a classe relacionada.
     * @param string $foreignKey - FK que representa o modelo na tabela relaiconada.
     * @param Repository $relatedRepository - Repositório da classe que contém o modelo.
     * @param string $localKey - Nome do atributo que representa o relacionamento no modelo.
     * @return void
     */
    protected function hasMany($model, string $relatedClass, string $foreignKey, Repository $relatedRepository, string $localKey = 'id')
    {
        $localKeyValue = $model->{'get' . ucfirst($localKey)}();
        $relatedModels = $relatedRepository->findAllBy($foreignKey, $localKeyValue);
        $model->{'set' . ucfirst($relatedClass)}($relatedModels);
    }

    /**
     * Define que o modelo pertence a outro modelo por um relacionamento de 1.
     * @param $model - Modelo
     * @param string $relatedClass - Nome do atributo que referencia a classe relacionada.
     * @param string $ownerKey - FK que representa o modelo na tabela que contém o modelo.
     * @param string $foreignKey - Nome do atribbuto que representa o modelo na classe que o contém.
     * @param Repository $relatedRepository - Repositório da classe que contém o modelo.
     * @return void
     */
    protected function belongsTo($model, string $relatedClass, string $ownerKey, string $foreignKey, Repository $relatedRepository)
    {
        $foreignKeyValue = $model->{'get' . ucfirst($foreignKey)}();

        if (is_object($foreignKeyValue)) {
            $foreignKeyValue = $foreignKeyValue->{'get' . ucfirst($ownerKey)}();
        }

        $relatedModel = $relatedRepository->findBy($ownerKey, $foreignKeyValue);
        $model->{'set' . ucfirst($relatedClass)}($relatedModel);
    }

    /**
     * Define que o modelo pertence a outro modelo por um relacionamento de muitos.
     * @param $model - Modelo
     * @param string $relatedClass - Nome do atributo que referencia a classe relacionada.
     * @param string $pivotTable - Tabela de ligação fraca.
     * @param string $foreignPivotKey - FK que representa o modelo na tabela de relação fraca.
     * @param string $relatedPivotKey - FK que representa a tabela relacionada na tabela de relação fraca.
     * @param string $localKey - Nome do atributo que representa o relacionamento no modelo.
     * @return void
     */
    protected function belongsToMany($model, string $relatedClass, string $pivotTable, string $foreignPivotKey, string $relatedPivotKey, string $localKey = 'id')
    {
        $relatedRepo = new ($relatedClass . 'Repository')($this->pdo);
        $relatedTable = $relatedRepo->getTable();

        $localId = $model->{'get' . ucfirst($localKey)}();

        $sql = "SELECT r.* FROM {$relatedTable} r
            INNER JOIN {$pivotTable} p ON r.id = p.{$relatedPivotKey}
            WHERE p.{$foreignPivotKey} = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$localId]);

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $relatedModels = array_map([$relatedRepo, 'mapToModel'], $results);

        $model->{'set' . ucfirst($relatedClass)}($relatedModels);
    }

    /**
     * Cria o relacionamento do modelo em uma tabela de ligação fraca.
     * @param $model - Modelo a ser relacionado.
     * @param string $pivotTable - Tabela de relação fraca.
     * @param string $foreignKey - FK que representa o modelo na tabela de relação fraca.
     * @param string $relatedKey - FK que representa a tabela a ser relacionada na tabela de relação fraca.
     * @param array $relatedIds - Identificadores dos registros a serem relacionados com modelo.
     * @return void
     */
    public function attach($model, string $pivotTable, string $foreignKey, string $relatedKey, array $relatedIds): void
    {
        $modelId = $model->getId();
        foreach ($relatedIds as $relatedId) {
            $stmt = $this->pdo->prepare("INSERT INTO $pivotTable ($foreignKey, $relatedKey) VALUES (?, ?)");
            $stmt->execute([$modelId, $relatedId]);
        }
    }

    /**
     * Remove o relacionamento do modelo em uma tabela de ligação fraca.
     * @param $model - Modelo a ter os relacionamentos removidos.
     * @param string $pivotTable - Tabela de ligação fraca.
     * @param string $foreignKey - FK que representa o modelo na tabela de relação fraca.
     * @param string $relatedKey - FK que representa a tabela relacionada na tabela de relação fraca.
     * @param array|null $relatedIds - Identificadores dos registros a terem o relacionamento removido com o modelo.
     * @return void
     */
    public function detach($model, string $pivotTable, string $foreignKey, string $relatedKey, ?array $relatedIds = null): void
    {
        $modelId = $model->getId();
        if ($relatedIds) {
            $in = implode(',', array_fill(0, count($relatedIds), '?'));
            $params = array_merge([$modelId], $relatedIds);
            $stmt = $this->pdo->prepare("DELETE FROM $pivotTable WHERE $foreignKey = ? AND $relatedKey IN ($in)");
            $stmt->execute($params);
        } else {
            $stmt = $this->pdo->prepare("DELETE FROM $pivotTable WHERE $foreignKey = ?");
            $stmt->execute([$modelId]);
        }
    }

    /**
     * Sincroniza os relacionamentos do modelo em uma tabela de ligação fraca.
     * @param $model - Modelo a ter os relacionamentos sincronizados.
     * @param string $pivotTable -  Tabela de ligação fraca.
     * @param string $foreignKey - FK que representa o modelo na tabela de relação fraca.
     * @param string $relatedKey - FK que representa a tabela relacionada na tabela de relação fraca.
     * @param array $relatedIds - Identificadores dos registros a serem sincronizados com o modelo.
     * @return void
     */
    public function sync($model, string $pivotTable, string $foreignKey, string $relatedKey, array $relatedIds): void
    {
        $this->detach($model, $pivotTable, $foreignKey, $relatedKey);
        $this->attach($model, $pivotTable, $foreignKey, $relatedKey, $relatedIds);
    }


    // Métodos auxiliares

    /**
     * Realiza a busca de um registro na tabela de dominio a partir dos parâmtros.
     * @param string $column - Coluna a ser buscada.
     * @param $value - Valor a ser filtrado.
     * @return object|mixed|null
     */
    public function findBy(string $column, $value): ?object
    {
        $this->queryBuilder();
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE $column = ? LIMIT 1");
        $stmt->execute([$value]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        $model = $data ? $this->mapToModel($data) : null;

        if ($model) {
            foreach ($this->with as $relation) {
                $this->loadRelation($model, $relation);
            }
        }

        return $model;
    }

    /**
     * Realiza a busca de todos os registros na tabela de dominio a partir dos parâmetros.
     * @param string $column - Coluna a ser buscada.
     * @param $value - Valor a ser filtrado.
     * @return array
     */
    public function findAllBy(string $column, $value): array
    {
        $this->queryBuilder();
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE $column = ?");
        $stmt->execute([$value]);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $models = array_map([$this, 'mapToModel'], $results);
        foreach ($models as $model) {
            foreach ($this->with as $relation) {
                $this->loadRelation($model, $relation);
            }
        }

        return $models;
    }

    public function mapToArray($model, $consideraRelacionamentos = false) {
        $reflection = new \ReflectionClass($model);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        $fields = [];
        $values = [];
        $placeholders = [];
        $updates = [];

        foreach ($methods as $method) {
            if (str_starts_with($method->name, 'get')) {
                $property = lcfirst(substr($method->name, 3));

                if (!$reflection->hasProperty($property)) {
                    continue;
                }

                $value = $model->{$method->name}();

                if ((is_object($value) && method_exists($value, 'getId')) && !($consideraRelacionamentos)) {
                    $value = $value->getId();
                }

                if ($property === 'id' && $value) {
                    $id = $value;
                } elseif ($property !== 'id') {
                    $fields[] = $property;
                    $values[] = $value;
                    $placeholders[] = '?';
                    $updates[] = "$property = ?";
                }
            }
        }

        return [$fields, $values, $placeholders, $updates, $id ?? null];
    }

    /**
     * Salva a instância do modelo passado por parâmetro.
     * Metodo utilizado tanto para insert quanto update.
     * @param $model
     * @return bool
     * @throws \Exception
     */
    public function save($model): bool
    {
        try {
            [$fields, $values, $placeholders, $updates, $id] = $this->mapToArray($model);

            if (isset($id)) {
                $sql = "UPDATE {$this->table} SET " . implode(", ", $updates) . " WHERE id = ?";
                $values = array_merge($values, [$id]);
            } else {
                $sql = "INSERT INTO {$this->table} (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $placeholders) . ")";
            }

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($values);

            if (!isset($id)) {
                $lastId = (int) $this->pdo->lastInsertId();
                if (method_exists($model, 'setId')) {
                    $model->setId($lastId);
                }
            }

            return $result;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * Salva a instância do modelo passado por parâmetro e salva também os registros de relacionamento one to many.
     * Metodo utilizado tanto para insert quanto update.
     * @param $model
     * @return bool
     * @throws \ReflectionException
     */
    public function saveWithRelations($model): bool
    {
        try {
            $this->begin();
            $this->save($model); // insert/update principal

            // Salvar relacionamentos hasMany
            $reflection = new \ReflectionClass($model);
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if (str_starts_with($method->name, 'get')) {
                    $property = lcfirst(substr($method->name, 3));
                    $value = $model->{$method->name}();
                    if (is_array($value)) {
                        foreach ($value as $relatedModel) {
                            $relatedRepoName = ucfirst(substr($property, 0, -1)) . 'Repository';
                            if (class_exists($relatedRepoName)) {
                                $relatedRepo = new $relatedRepoName($this->pdo);
                                $relatedRepo->setControlaTransacao($this->controlaTransacao);

                                // Tenta setar a FK (ex: setUserId)
                                $setter = 'set' . ucfirst($this->table) . 'Id';
                                if (method_exists($relatedModel, $setter)) {
                                    $relatedModel->$setter($model->getId());
                                }

                                $relatedRepo->save($relatedModel);
                            }
                        }
                    }
                }
            }

            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * Remove um registro do banco de dados.
     * @param object $model - Instância do modelo a ser removido.
     * @return bool - Retorna verdadeiro se a remoção for bem-sucedida.
     */
    public function remove(object $model): bool
    {
        if (!method_exists($model, 'getId')) {
            throw new \InvalidArgumentException("O modelo não possui o método getId().");
        }

        $id = $model->getId();
        if (!$id) {
            throw new \InvalidArgumentException("ID do modelo não pode ser nulo para remoção.");
        }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
            $result = $stmt->execute([$id]);

            return $result;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Define se executará controle de transação em operações com mais de uma alteração no banco.
     * @param bool $controlaTransacao
     * @return void
     */
    public function setControlaTransacao(bool $controlaTransacao)
    {
        $this->controlaTransacao = $controlaTransacao;
    }

    protected function begin()
    {
        if ($this->controlaTransacao) {
            $this->pdo->beginTransaction();
        }
    }

    protected function commit()
    {
        if ($this->controlaTransacao) {
            $this->pdo->commit();
        }
    }

    protected function rollback()
    {
        if ($this->controlaTransacao) {
            $this->pdo->rollBack();
        }
    }
}
