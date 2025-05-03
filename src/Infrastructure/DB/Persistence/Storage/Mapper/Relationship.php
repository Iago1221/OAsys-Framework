<?php

namespace Framework\Infrastructure\DB\Persistence\Storage\Mapper;

class Relationship
{
    const TYPE_ONE_TO_ONE = 'one_to_one';
    const TYPE_ONE_TO_MANY = 'one_to_many';
    const TYPE_MANY_TO_MANY = 'many_to_many';

    private string $type;
    private IMapper $mapper;
    private string $foreignKey;
    private string $localKey;
    private ?string $pivotTable;
    private ?string $pivotLocalKey;
    private ?string $pivotForeignKey;

    public function __construct(
        string $type,
        IMapper $mapper,
        string $foreignKey,
        string $localKey,
        ?string $pivotTable = null,
        ?string $pivotLocalKey = null,
        ?string $pivotForeignKey = null
    ) {
        $this->type = $type;
        $this->mapper = $mapper;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
        $this->pivotTable = $pivotTable;
        $this->pivotLocalKey = $pivotLocalKey;
        $this->pivotForeignKey = $pivotForeignKey;
    }

    public function getType(): string { return $this->type; }
    public function getMapper(): IMapper { return $this->mapper; }
    public function getForeignKey(): string { return $this->foreignKey; }
    public function getLocalKey(): string { return $this->localKey; }
    public function getPivotTable(): ?string { return $this->pivotTable; }
    public function getPivotLocalKey(): ?string { return $this->pivotLocalKey; }
    public function getPivotForeignKey(): ?string { return $this->pivotForeignKey; }
}