<?php

namespace Framework\Infrastructure\CLI\Scheduler;

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

        foreach ($tenants as $tenant) {
            echo "\n>> Executando tarefas para: {$tenant['nome']} \n";
            TenantLoader::conectar($tenant);

            foreach ($this->tasks as $name => $task) {
                if ($taskName && $taskName !== $name) continue;

                try {
                    echo " -> Executando tarefa: {$name} \n";
                    $task->run($tenant);
                } catch (\Throwable $e) {
                    echo " !! Erro na tarefa {$name} para {$tenant['nome']}: {$e->getMessage()} \n";
                }
            }
        }

        echo "\n✅ Agendador finalizado.\n";
    }
}
