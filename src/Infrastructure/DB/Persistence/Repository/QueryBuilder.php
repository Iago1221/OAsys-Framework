<?php

namespace Framework\Infrastructure\DB\Persistence\Repository;

class QueryBuilder {
  protected \PDO $pdo;
  protected string $table;
  protected array $where = [];
  protected array $bindings = [];
  protected array $select = ['*'];

  public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
  }

  public function table(string $table): self {
        $this->table = $table;
    return $this;
  }

  public function select(array $columns): self {
        $this->select = $columns;
    return $this;
  }

  public function where(string $column, string $operator, $value): self {
        $this->where[] = "$column $operator ?";
    $this->bindings[] = $value;
    return $this;
  }

  public function get(): array {
        $sql = "SELECT " . implode(', ', $this->select) . " FROM {$this->table}";

    if ($this->where) {
              $sql .= " WHERE " . implode(" AND ", $this->where);
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($this->bindings);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function first(): ?array {
        $result = $this->get();
    return $result[0] ?? null;
  }
}