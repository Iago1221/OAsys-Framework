<?php

namespace Framework\Infrastructure\DB\Persistence\Storage;

/**
 * Interface padrão para storage (Objeto que gerência a conexão com um banco de dados).
 * @since 24/02/2025
 * @author Iago Oliveira <iago.oliveira@gmail.com>
 */
interface IStorage
{
    /**
     * Metodo que busca as informações na storage de acordo com o parâmetro.
     * @param array $aFilters - Array associativo (coluna - valor) utilizado para filtrar os registros.
     * @return array
     */
    public function get(array $aFilters = [], ?int $iLimit = null, ?int $iOffset = null, array $aOrderBy = []): array;

    /**
     * Metodo que adiciona um registro na storage.
     * @param array $aData - Array associativo (coluna - valor) utilizado para inserir os dados.
     * @return mixed - Retorna o identificador do objeto inserido ou void.
     */
    public function add(array $aData): mixed;

    /**
     * Metodo que atualiza registros na storage de acordo com os parâmetros.
     * @param array $aFilters - Array associativo (coluna - valor) utilizado para filtrar os registros que serão atualizados.
     * @param array $aData - Array associativo (coluna - valor) utilizado para atualizar os dados.
     * @return void
     */
    public function update(array $aFilters, array $aData): void;

    /**
     * Metodo que remove registros na storage de acordo com os parâmetros.
     * @param array $aFilters - Array associativo (coluna - valor) utilizado para filtrar os registros que serão removidos.
     * @return void
     */
    public function delete(array $aFilters): void;

    /**
     * Metodo utilizado para verificar se existe registros de acordo com os parâmetros na storage.
     * @param array $aFilters - Array associativo (coluna - valor) utilizado para filtrar os registros.
     * @return boolean
     */
    public function exists(array $aFilters): bool;

    /**
     * Executa o sql passado por parâmetro.
     * @param string $sSql
     * @return void
     */
    public function exec(string $sSql): void;

    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
}
