<?php

namespace Framework\Infrastructure\DynamicReport;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Infrastructure\Mensagem;

/**
 * Executa definição validada usando o repositório raiz e a API Repository (filterBy, with, orderBy, limit, get).
 */
class DynamicReportExecutor
{
    public function __construct(
        private DynamicReportDefinitionValidator $validator = new DynamicReportDefinitionValidator()
    ) {
    }

    /**
     * @param array|string $definition
     */
    public function execute(\PDO $pdo, $definition): DynamicReportExecutionResult
    {
        $def = $this->validator->validate($definition);
        $schema = DynamicReportSchemaRegistry::getSchema($def['pacote'], $def['rootEntityKey']);
        if (!$schema) {
            throw new Mensagem('Esquema não encontrado para execução.');
        }

        $repoClass = $schema->getRepositoryClass();
        if (!class_exists($repoClass)) {
            throw new Mensagem('Repositório configurado no esquema não existe: ' . $repoClass);
        }

        $repository = new $repoClass($pdo);
        if (!$repository instanceof Repository) {
            throw new Mensagem('Repositório do relatório dinâmico deve estender Repository.');
        }

        $with = $schema->resolveRequiredWith($def['select'], $def['filters']);
        if ($with !== []) {
            $repository->with($with);
        }

        foreach ($def['filters'] as $filter) {
            $field = $schema->getField($filter['name']);
            if ($field === null || !$field->isFilterable()) {
                continue;
            }
            $col = $field->getFilterColumn();
            $repository->filterBy([
                [
                    'name' => $col,
                    'operator' => $filter['operator'],
                    'value' => $filter['value'],
                ],
            ]);
        }

        if (isset($def['orderBy']) && is_array($def['orderBy']) && !empty($def['orderBy']['field'])) {
            $obField = $schema->getField($def['orderBy']['field']);
            if ($obField && $obField->getSortColumn()) {
                $repository->orderBy($obField->getSortColumn(), $def['orderBy']['dir']);
            }
        }

        $repository->limit((int) $def['limit']);
        $models = $repository->get();

        $columns = [];
        foreach ($def['select'] as $key) {
            $f = $schema->getField($key);
            $columns[] = [
                'key' => $key,
                'label' => $f ? $f->getLabel() : $key,
            ];
        }

        $rows = [];
        foreach ($models as $model) {
            $row = [];
            foreach ($def['select'] as $key) {
                $field = $schema->getField($key);
                $raw = $this->extractValue($model, $key);
                $row[$key] = $this->applyDisplayMap($field?->getDisplayValueMap(), $raw);
            }
            $rows[] = $row;
        }

        return new DynamicReportExecutionResult($columns, $rows);
    }

    /**
     * @param array<int|string, string>|null $map
     */
    private function applyDisplayMap(?array $map, mixed $raw): mixed
    {
        if ($map === null || $raw === null) {
            return $raw;
        }

        if (is_bool($raw)) {
            return $raw;
        }

        $candidates = [];
        if (is_int($raw) || is_float($raw)) {
            $candidates[] = $raw;
            $candidates[] = (int) $raw;
            $candidates[] = (string) (int) $raw;
        } elseif (is_string($raw) && $raw !== '') {
            $candidates[] = $raw;
            if (is_numeric($raw)) {
                $n = (int) $raw;
                $candidates[] = $n;
                $candidates[] = (string) $n;
            }
        } else {
            return $raw;
        }

        foreach ($candidates as $k) {
            if (array_key_exists($k, $map)) {
                return $map[$k];
            }
        }

        return $raw;
    }

    private function extractValue(object $model, string $fieldKey): mixed
    {
        $parts = explode('.', $fieldKey);
        $current = $model;
        foreach ($parts as $segment) {
            $getter = 'get' . ucfirst($segment);
            if (!is_object($current) || !method_exists($current, $getter)) {
                return null;
            }
            $current = $current->{$getter}();
        }

        if (is_object($current)) {
            if (method_exists($current, 'getId')) {
                return $current->getId();
            }
            if (method_exists($current, '__toString')) {
                return (string) $current;
            }

            return null;
        }

        return $current;
    }
}
