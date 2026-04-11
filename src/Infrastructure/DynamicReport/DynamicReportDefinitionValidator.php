<?php

namespace Framework\Infrastructure\DynamicReport;

use Framework\Infrastructure\Mensagem;

/**
 * Valida definição de relatório (array/JSON decodificado) contra o esquema registrado.
 */
class DynamicReportDefinitionValidator
{
    private const MAX_JSON_BYTES = 65536;

    private const GLOBAL_OPERATORS = [
        'IGUAL', 'DIFERENTE', 'CONTEM', 'MAIOR', 'MENOR', 'MAIOR IGUAL', 'MENOR IGUAL', 'EM',
    ];

    /**
     * @param array|string $definition Array já decodificado ou string JSON
     * @return array<string, mixed> Definição normalizada
     */
    public function validate($definition): array
    {
        if (is_string($definition)) {
            if (strlen($definition) > self::MAX_JSON_BYTES) {
                throw new Mensagem('Definição do relatório excede o tamanho máximo permitido.');
            }
            $decoded = json_decode($definition, true);
            if (!is_array($decoded)) {
                throw new Mensagem('Definição do relatório não é um JSON válido.');
            }
            $definition = $decoded;
        }

        $schemaVersion = (int) ($definition['schemaVersion'] ?? 0);
        if ($schemaVersion !== 1) {
            throw new Mensagem('Versão de esquema do relatório não suportada.');
        }

        $package = $definition['pacote'] ?? '';
        $rootEntityKey = $definition['rootEntityKey'] ?? '';
        if ($package === '' || $rootEntityKey === '') {
            throw new Mensagem('Pacote e entidade raiz são obrigatórios na definição.');
        }

        $entitySchema = DynamicReportSchemaRegistry::getSchema((string) $package, (string) $rootEntityKey);
        if (!$entitySchema) {
            throw new Mensagem('Combinação de pacote e entidade não possui esquema de relatório dinâmico registrado.');
        }

        $select = $definition['select'] ?? null;
        if (!is_array($select) || $select === []) {
            throw new Mensagem('Selecione ao menos uma coluna no relatório.');
        }

        foreach ($select as $token) {
            if (!is_string($token) || $entitySchema->getField($token) === null) {
                throw new Mensagem('Coluna inválida na definição do relatório: ' . (string) $token);
            }
        }

        $filters = $definition['filters'] ?? [];
        if (!is_array($filters)) {
            throw new Mensagem('Filtros do relatório devem ser uma lista.');
        }

        foreach ($filters as $filter) {
            if (!is_array($filter)) {
                throw new Mensagem('Formato de filtro inválido.');
            }
            $name = $filter['name'] ?? '';
            $operator = strtoupper((string) ($filter['operator'] ?? ''));
            if (!is_string($name) || $name === '') {
                throw new Mensagem('Nome do filtro é obrigatório.');
            }
            $field = $entitySchema->getField($name);
            if ($field === null || !$field->isFilterable()) {
                throw new Mensagem('Campo não pode ser utilizado como filtro: ' . $name);
            }
            if (!in_array($operator, self::GLOBAL_OPERATORS, true)) {
                throw new Mensagem('Operador de filtro não permitido: ' . $operator);
            }
            if (!in_array($operator, $field->getFilterOperators(), true)) {
                throw new Mensagem('Operador não permitido para o campo: ' . $name);
            }
        }

        if (isset($definition['orderBy'])) {
            $orderBy = $definition['orderBy'];
            if (!is_array($orderBy)) {
                throw new Mensagem('Ordenação inválida.');
            }
            $fieldKey = $orderBy['field'] ?? '';
            if (!is_string($fieldKey) || $fieldKey === '') {
                throw new Mensagem('Campo de ordenação é obrigatório.');
            }
            $field = $entitySchema->getField($fieldKey);
            if ($field === null || !$field->isSortable() || $field->getSortColumn() === null) {
                throw new Mensagem('Campo não pode ser utilizado na ordenação: ' . $fieldKey);
            }
            $dir = strtoupper((string) ($orderBy['dir'] ?? 'ASC'));
            if (!in_array($dir, ['ASC', 'DESC'], true)) {
                throw new Mensagem('Direção de ordenação inválida.');
            }
        }

        $limit = isset($definition['limit']) ? (int) $definition['limit'] : $entitySchema->getDefaultLimit();
        if ($limit < 1) {
            $limit = $entitySchema->getDefaultLimit();
        }
        if ($limit > $entitySchema->getMaxLimit()) {
            $limit = $entitySchema->getMaxLimit();
        }

        return [
            'schemaVersion' => 1,
            'pacote' => (string) $package,
            'rootEntityKey' => (string) $rootEntityKey,
            'select' => array_values(array_unique(array_map('strval', $select))),
            'filters' => $this->normalizeFilters($filters),
            'orderBy' => isset($definition['orderBy']) && is_array($definition['orderBy'])
                ? [
                    'field' => (string) ($definition['orderBy']['field'] ?? ''),
                    'dir' => strtoupper((string) ($definition['orderBy']['dir'] ?? 'ASC')) === 'DESC' ? 'DESC' : 'ASC',
                ]
                : null,
            'limit' => $limit,
        ];
    }

    /**
     * @param array<int, mixed> $filters
     * @return array<int, array{name: string, operator: string, value: mixed}>
     */
    private function normalizeFilters(array $filters): array
    {
        $out = [];
        foreach ($filters as $filter) {
            if (!is_array($filter)) {
                continue;
            }
            $out[] = [
                'name' => (string) ($filter['name'] ?? ''),
                'operator' => strtoupper((string) ($filter['operator'] ?? 'IGUAL')),
                'value' => $filter['value'] ?? null,
            ];
        }

        return $out;
    }
}
