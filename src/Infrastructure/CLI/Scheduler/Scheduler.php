<?php

namespace Framework\Infrastructure\CLI\Scheduler;

use Framework\Core\Main;
use Framework\Infrastructure\Tenant\TenantLoader;

class Scheduler
{
    /** @var TaskInterface[] */
    private array $tasks = [];

    public function addTask(TaskInterface $task): void
    {
        $this->tasks[$task->getName()] = $task;
    }

    public function run(string $taskName = null): void
    {
        $tenants = TenantLoader::listarTodos();

        foreach (array_keys($tenants) as $tenant) {
            echo "\n>> Executando tarefas para: {$tenant} \n";
            TenantLoader::conectar($tenant);
            Main::setTenant($tenant);

            foreach ($this->tasks as $name => $task) {
                if ($taskName && $taskName !== $name) continue;

                try {
                    echo " -> Executando tarefa: {$name} \n";
                    $task->run($tenant);
                } catch (\Throwable $e) {
                    echo " !! Erro na tarefa {$name} para {$tenant}: {$e->getMessage()} \n";
                }
            }
        }

        echo "\n✅ Agendador finalizado.\n";
    }
}
