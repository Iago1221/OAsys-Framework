<?php

namespace Framework\Interface\Infrastructure\Persistence\Sistema\Evento;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Evento\OutboxEvento;

class OutboxEventoRepository extends Repository
{
    /** @return OutboxEvento[] */
    public function findPendentes(int $limite = 50): array
    {
        $sql = "SELECT * FROM {$this->getTable()} WHERE publicado = false ORDER BY id ASC LIMIT :limite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('limite', $limite, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map([$this, 'mapToModel'], $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function marcarPublicado(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE {$this->getTable()} SET publicado = true, data_publicacao = :data WHERE id = :id"
        );
        $stmt->execute(['data' => date('Y-m-d H:i:s'), 'id' => $id]);
    }

    protected function getModelClass(): string
    {
        return OutboxEvento::class;
    }

    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function getTableName(): string
    {
        return 'outbox_eventos';
    }
}
