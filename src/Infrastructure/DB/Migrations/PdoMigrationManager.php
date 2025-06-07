<?php

namespace Framework\Infrastructure\DB\Migrations;

use Framework\Infrastructure\DB\Persistence\Storage\PdoStorage;

/**
 * Classe que gerencia as execuções de atualizações na storage.
 *
 * @since 16/03/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
final class PdoMigrationManager implements IMigration
{
    private $oStorage;
    private $migrationsDir;

    public function __construct(PdoStorage $oStorage, $migrationsDir)
    {
        $this->oStorage = $oStorage;
        $this->migrationsDir = $migrationsDir;
    }

    /**
     * Executa as alterações pertinentes da migration passada por parâmetro na storage.
     * @param $migrationName
     * @return void
     */
    public function up($migrationName = null)
    {
        $this->createMigrationsTableIfNotExists();
        $executedMigrations = $this->getExecutedMigrations();
        $sFile = $this->migrationsDir . '/' . $migrationName . '.php';

        if (!file_exists($sFile)) {
            echo "Migration '" . $migrationName . "' não encontrada.\n";
            return;
        }

        if (!in_array($migrationName, $executedMigrations)) {
            require_once $sFile;

            $migrationClass = $this->getMigrationClassName($migrationName);
            $migration = new $migrationClass($this->oStorage);
            $migration->up();

            $this->markMigrationAsExecuted($migrationName);
            echo "Migration '$migrationName' executada com sucesso.\n";
            return;
        }

        echo "Migration '$migrationName' já foi executada.\n";
    }

    /**
     * Desfaz as alterações realizadas na storage pela migration passada por parâmetro.
     * @param $migrationName
     * @return void
     */
    public function down($migrationName = null)
    {
        $this->createMigrationsTableIfNotExists();
        $executedMigrations = $this->getExecutedMigrations();
        $sFile = $this->migrationsDir . '/' . $migrationName . '.php';

        if (!file_exists($sFile)) {
            echo "Migration '" . $migrationName . "' não encontrada.\n";
            return;
        }

        if (!in_array($migrationName, $executedMigrations)) {
            require_once $sFile;

            $migrationClass = $this->getMigrationClassName($migrationName);
            $migration = new $migrationClass($this->oStorage);

            $migration->down();

            $this->markMigrationAsRolledBack($migrationName);
            echo "Migration '$migrationName' revertida com sucesso.\n";
            return;
        }

        echo "Migration '$migrationName' ainda não foi executada.\n";
    }

    /**
     * Executa todas as migrations que ainda não foram executadas.
     * @return void
     */
    public function runMigrations()
    {
        $this->createMigrationsTableIfNotExists();
        $executedMigrations = $this->getExecutedMigrations();
        $migrationFiles = glob($this->migrationsDir . '/*.php');
        $bExecuted = false;

        foreach ($migrationFiles as $file) {
            $migrationName = basename($file, '.php');

            if (!in_array($migrationName, $executedMigrations)) {
                $bExecuted = true;
                require_once $file;

                $migrationClass = $this->getMigrationClassName($migrationName);
                $migration = new $migrationClass($this->oStorage);
                $migration->up();

                $this->markMigrationAsExecuted($migrationName);
                echo "Migration '$migrationName' executada com sucesso.\n";
            }
        }

        echo $bExecuted ? "Todas migrations disponíveis foram executadas.\n" : "Não existe nenhuma migration disponível parar ser executada.\n";
    }

    /**
     * Remove todas as migrations executadas.
     * @param int $iSteps - Quantidade de migrations a serem revertidas.
     * @return void
     */
    public function rollbackMigrations($iSteps = 1)
    {
        $this->createMigrationsTableIfNotExists();
        $executedMigrations = $this->getExecutedMigrations();
        $migrationsToRollback = array_slice(array_reverse($executedMigrations), 0, $iSteps);
        $bExecuted = false;

        foreach ($migrationsToRollback as $migrationName) {
            $file = $this->migrationsDir . '/' . $migrationName . '.php';

            if (file_exists($file)) {
                $bExecuted = true;
                require_once $file;

                $migrationClass = $this->getMigrationClassName($migrationName);
                $migration = new $migrationClass($this->oStorage);

                $migration->down();

                $this->markMigrationAsRolledBack($migrationName);
                echo "Migration '$migrationName' revertida com sucesso.\n";
            }
        }

        echo $bExecuted ? "Foram revertidas as últimas {$iSteps} migrations executadas.\n" : "Nenhuma migration a ser revertida.\n";
    }

    /**
     * Cria a tabela de controle de migrations caso ela não exista.
     * @return void
     */
    private function createMigrationsTableIfNotExists()
    {
        $this->createSchemaIfNotExists();
        $sql = "CREATE TABLE IF NOT EXISTS oasys.migrations (
            id serial4 PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $this->oStorage->exec($sql);
    }

    /**
     * Cria o schema do framework caso ele não exista.
     */
    private function createSchemaIfNotExists()
    {
        $sql = "CREATE SCHEMA oasys IF NOT EXISTS;";

        $this->oStorage->exec($sql);
    }

    /**
     * Retorna todas as migrations já executadas.
     * @return array|false
     */
    private function getExecutedMigrations()
    {
        return $this->oStorage->query("SELECT migration FROM oasys.migrations ORDER BY executed_at ASC");
    }

    /**
     * Marca a migration como executada.
     * @param $migrationName
     * @return void
     */
    private function markMigrationAsExecuted($migrationName)
    {
        $sql = "INSERT INTO oasys.migrations (migration) VALUES ('$migrationName')";
        $this->oStorage->exec($sql);
    }

    /**
     * Remove a migration das executadas.
     * @param $migrationName
     * @return void
     */
    private function markMigrationAsRolledBack($migrationName)
    {
        $sql = "DELETE FROM oasys.migrations WHERE migration = '$migrationName'";
        $this->oStorage->exec($sql);
    }

    /**
     * Retorna o nome da classe da migration conforme o nome do arquivo passado por parâmetro.
     * @param $migrationName
     * @return string
     */
    private function getMigrationClassName($migrationName)
    {
        return 'version_' . $migrationName;
    }
}
