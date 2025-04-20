    <?php

require __DIR__ . '/../../../../vendor/autoload.php';

$sMigrationsDir = __DIR__ . '/../../../Sistema/Infrastructure/Migrations';
$oManager = new Framework\Infrastructure\DB\Migrations\PdoMigrationManager(Framework\Core\Main::getPdoStorage(), $sMigrationsDir);

if ($argc < 2) {
    echo "Uso: migrate <comando> [argumentos]\n";
    echo "Comandos disponíveis:\n";
    echo "  run               Executa todas as migrations pendentes.\n";
    echo "  rollback          Reverte a última migration executada.\n";
    echo "  up <migration>    Executa o 'up' de uma migration específica.\n";
    echo "  down <migration>  Executa o 'down' de uma migration específica.\n";
    exit(1);
}

$sCommand = $argv[1];

switch ($sCommand) {
    case 'run':
        $oManager->runMigrations();
        break;

    case 'rollback':
        $steps = isset($argv[2]) ? (int)$argv[2] : 1;
        $oManager->rollbackMigrations($steps);
        break;

    case 'up':
        if ($argc < 3) {
            echo "Erro: Nome da migration não fornecido.\n";
            exit(1);
        }
        $migrationName = $argv[2];
        $oManager->up($migrationName);
        break;

    case 'down':
        if ($argc < 3) {
            echo "Erro: Nome da migration não fornecido.\n";
            exit(1);
        }
        $migrationName = $argv[2];
        $oManager->down($migrationName);
        break;

    default:
        echo "Comando desconhecido: $sCommand\n";
        exit(1);
}
