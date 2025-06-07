<?php

namespace Framework\Infrastructure\DB\Persistence\Storage;

use PDO;
use PDOException;

/**
 * Classe padrão para storage utilizando PDO.
 *
 * @since 24/02/2025
 * @author Iago Oliveira <iago.oliveira@gmail.com>
 */
class PdoStorage extends GenericStorage
{
    /** @var PDO */
    private $pdo;

    /**
     * Construtor da classe.
     * @param PDO $pdo - Instância de PDO configurada para o banco de dados.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Metodo que busca as informações na storage de acordo com os filtros, paginação e ordenação.
     * @param array $aFilters - Array associativo (coluna - valor) utilizado para filtrar os registros.
     * @param int|null $iLimit - Quantidade de registros por página.
     * @param int|null $iOffset - Deslocamento para paginação.
     * @param array $aOrderBy - Critérios de ordenação (ex: ['coluna' => 'ASC']).
     * @return array
     */
    public function get(array $aFilters = [], ?int $iLimit = null, ?int $iOffset = null, array $aOrderBy = []): array
    {
        try {
            $sWhere = $this->buildWhereClause($aFilters, 'LIKE');
            $sOrderBy = $this->buildOrderByClause($aOrderBy);
            $sLimitOffset = $this->buildLimitOffsetClause($iLimit, $iOffset);

            foreach ($aFilters as $sField => $sValue) {
                $aFilters[$sField] = '%' . $sValue . '%';
            }

            $sQuery = "SELECT * FROM {$this->sFrom}";
            if ($sWhere) {
                $sQuery .= " WHERE {$sWhere}";
            }
            if ($sOrderBy) {
                $sQuery .= " ORDER BY {$sOrderBy}";
            }
            if ($sLimitOffset) {
                $sQuery .= " {$sLimitOffset}";
            }

            $stmt = $this->pdo->prepare($sQuery);
            $stmt->execute($aFilters);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \RuntimeException("Erro ao buscar dados: " . $e->getMessage());
        }
    }

    /**
     * Metodo que busca o primeiro registro igual aos filtros passados por parâmetro.
     * @param array $aFilters - Array associativo (coluna - valor) utilizado para filtrar os registros.
     * @param array $aOrderBy - Critérios de ordenação (ex: ['coluna' => 'ASC']).
     * @return object|null|false - Pode retornar o objeto do registro, nulo caso não encontre registro ou false caso ocorra um erro na execução.
     */
    public function find(array $aFilters = [], array $aOrderBy = []) {
        try {
            $sWhere = $this->buildWhereClause($aFilters);
            $sOrderBy = $this->buildOrderByClause($aOrderBy);
            $sLimitOffset = $this->buildLimitOffsetClause(1, 0);

            $sQuery = "SELECT * FROM {$this->sFrom}";
            if ($sWhere) {
                $sQuery .= " WHERE {$sWhere}";
            }
            if ($sOrderBy) {
                $sQuery .= " ORDER BY {$sOrderBy}";
            }
            if ($sLimitOffset) {
                $sQuery .= " {$sLimitOffset}";
            }

            $stmt = $this->pdo->prepare($sQuery);
            $stmt->execute($aFilters);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \RuntimeException("Erro ao buscar dados: " . $e->getMessage());
        }
    }

    /**
     * Metodo que adiciona um registro na storage.
     * @param array $aData - Array associativo (coluna - valor) utilizado para inserir os dados.
     * @return mixed
     */
    public function add(array $aData): mixed
    {
        try {
            $sColumns = implode(', ', array_keys($aData));
            $sValues = ':' . implode(', :', array_keys($aData));
            $sQuery = "INSERT INTO {$this->sFrom} ({$sColumns}) VALUES ({$sValues})";
            $stmt = $this->pdo->prepare($sQuery);
            $stmt->execute($aData);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new \RuntimeException("Erro ao adicionar dados: " . $e->getMessage());
        }
    }

    /**
     * Metodo que atualiza registros na storage de acordo com os filtros.
     * @param array $aFilters - Array associativo (coluna - valor) utilizado para filtrar os registros.
     * @param array $aData - Array associativo (coluna - valor) utilizado para atualizar os dados.
     * @return void
     */
    public function update(array $aFilters, array $aData): void
    {
        try {
            $sSet = $this->buildSetClause($aData);
            $sWhere = $this->buildWhereClause($aFilters);
            $sQuery = "UPDATE {$this->sFrom} SET {$sSet} WHERE {$sWhere}";
            $stmt = $this->pdo->prepare($sQuery);
            $stmt->execute(array_merge($aData, $aFilters));
        } catch (PDOException $e) {
            throw new \RuntimeException("Erro ao atualizar dados: " . $e->getMessage());
        }
    }

    /**
     * Metodo que remove registros na storage de acordo com os filtros.
     * @param array $aFilters - Array associativo (coluna - valor) utilizado para filtrar os registros.
     * @return void
     */
    public function delete(array $aFilters): void
    {
        try {
            $sWhere = $this->buildWhereClause($aFilters);
            $sQuery = "DELETE FROM {$this->sFrom} WHERE {$sWhere}";
            $stmt = $this->pdo->prepare($sQuery);
            $stmt->execute($aFilters);
        } catch (PDOException $e) {
            throw new \RuntimeException("Erro ao deletar dados: " . $e->getMessage());
        }
    }

    /**
     * Metodo que verifica se existem registros de acordo com os filtros.
     * @param array $aFilters - Array associativo (coluna - valor) utilizado para filtrar os registros.
     * @return bool
     */
    public function exists(array $aFilters): bool
    {
        try {
            $sWhere = $this->buildWhereClause($aFilters);
            $sQuery = "SELECT COUNT(*) FROM {$this->sFrom} WHERE {$sWhere}";
            $stmt = $this->pdo->prepare($sQuery);
            $stmt->execute($aFilters);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new \RuntimeException("Erro ao verificar existência de dados: " . $e->getMessage());
        }
    }

    /**
     * Metodo auxiliar para construir a cláusula WHERE.
     * @param array $aFilters - Array associativo (coluna - valor) utilizado para filtrar os registros.
     * @param string $sOperator - Operador do filtro
     * @return string
     */
    private function buildWhereClause(array $aFilters, string $sOperator = '='): string
    {
        $aConditions = [];
        foreach ($aFilters as $sColumn => $sValue) {
            $aConditions[] = "{$sColumn} {$sOperator} :{$sColumn}";
        }
        return implode(' AND ', $aConditions);
    }

    /**
     * Metodo auxiliar para construir a cláusula SET.
     * @param array $aData - Array associativo (coluna - valor) utilizado para atualizar os dados.
     * @return string
     */
    private function buildSetClause(array $aData): string
    {
        $aConditions = [];
        foreach ($aData as $sColumn => $sValue) {
            $aConditions[] = "{$sColumn} = :{$sColumn}";
        }
        return implode(', ', $aConditions);
    }

    /**
     * Metodo auxiliar para construir a cláusula ORDER BY.
     * @param array $aOrderBy - Critérios de ordenação (ex: ['coluna' => 'ASC']).
     * @return string
     */
    private function buildOrderByClause(array $aOrderBy): string
    {
        if (empty($aOrderBy)) {
            return '';
        }

        $aConditions = [];
        foreach ($aOrderBy as $sColumn => $sDirection) {
            $sDirection = strtoupper($sDirection) === 'DESC' ? 'DESC' : 'ASC';
            $aConditions[] = "{$sColumn} {$sDirection}";
        }
        return implode(', ', $aConditions);
    }

    /**
     * Metodo auxiliar para construir a cláusula LIMIT e OFFSET.
     * @param int|null $iLimit - Quantidade de registros por página.
     * @param int|null $iOffset - Deslocamento para paginação.
     * @return string
     */
    private function buildLimitOffsetClause(?int $iLimit, ?int $iOffset): string
    {
        $sClause = '';
        if ($iLimit !== null) {
            $sClause .= " LIMIT {$iLimit}";
        }
        if ($iOffset !== null) {
            $sClause .= " OFFSET {$iOffset}";
        }
        return $sClause;
    }

    /** @inheritDoc */
    public function exec(string $sSql): void
    {
        $this->pdo->exec($sSql);
    }

    /**
     * Metodo que executa a consulta passada por parâmetro na storage.
     * @param $sSql
     * @return array|false
     */
    public function query(string $sSql): array|false
    {
        $oQuery = $this->pdo->query($sSql);
        return $oQuery ? $oQuery->fetchAll(PDO::FETCH_ASSOC) : false;
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollback(): void
    {
        $this->pdo->rollBack();
    }

    function executarArquivoSQL(string $caminhoArquivo): void
    {
        if (!file_exists($caminhoArquivo)) {
            throw new \RuntimeException("Arquivo não encontrado: $caminhoArquivo");
        }

        $sql = file_get_contents($caminhoArquivo);

        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        try {
            $this->pdo->beginTransaction();
            $this->pdo->exec($sql);
            $this->pdo->commit();
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            throw new \RuntimeException("Erro ao executar o script SQL: " . $e->getMessage());
        }
    }
}
