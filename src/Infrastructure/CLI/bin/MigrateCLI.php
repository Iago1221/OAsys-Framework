<?php

namespace Framework\Infrastructure\CLI\bin;

use Framework\Core\Main;
use Framework\Infrastructure\DB\Migrations\PdoMigrationManager;

/**
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 * @since 15/06/2025
 */
class MigrateCLI
{
    protected $migrationsDir;
    protected $command;
    protected $manager;

    public function __construct(string $migrationsDir)
    {
        $this->setMigrationsDir($migrationsDir);
        $this->validaArgs();
        $this->setCommand();
    }

    protected  function setMigrationsDir(string $migrationsDir)
    {
        $this->migrationsDir = $migrationsDir;
    }

    protected function validaArgs()
    {
        global $argc;

        if ($argc < 2) {
            echo "Uso: migrate <comando> [argumentos]\n";
            echo "Comandos disponíveis:\n";
            echo "  run               Executa todas as migrations pendentes.\n";
            echo "  rollback          Reverte a última migration executada.\n";
            echo "  up <migration>    Executa o 'up' de uma migration específica.\n";
            echo "  down <migration>  Executa o 'down' de uma migration específica.\n";
            exit(1);
        }
    }

    protected function setCommand()
    {
        global $argv;
        $this->command = $argv[1];
    }

    public function execute()
    {
        global $argv, $argc;

        switch ($this->command) {
            case 'run':
                $this->getManager()->runMigrations();
                break;

            case 'rollback':
                $steps = isset($argv[2]) ? (int)$argv[2] : 1;
                $this->getManager()->rollbackMigrations($steps);
                break;

            case 'up':
                if ($argc < 3) {
                    echo "Erro: Nome da migration não fornecido.\n";
                    exit(1);
                }
                $migrationName = $argv[2];
                $this->getManager()->up($migrationName);
                break;

            case 'down':
                if ($argc < 3) {
                    echo "Erro: Nome da migration não fornecido.\n";
                    exit(1);
                }
                $migrationName = $argv[2];
                $this->getManager()->down($migrationName);
                break;

            default:
                echo "Comando desconhecido: $this->command\n";
                exit(1);
        }
    }

    protected function getManager()
    {
        if (!$this->manager) {
            $this->manager = new PdoMigrationManager(Main::getPdoStorage(), $this->migrationsDir);
        }

        return $this->manager;
    }
}
