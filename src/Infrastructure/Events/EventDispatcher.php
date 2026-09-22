<?php

namespace Framework\Infrastructure\Events;

use Framework\Core\Main;
use Framework\Interface\Domain\Evento\OutboxEvento;
use Framework\Interface\Infrastructure\Persistence\Sistema\Evento\OutboxEventoRepository;

/**
 * Publica eventos de domínio na tabela de outbox (oasys.outbox_eventos), na MESMA
 * conexão/transação corrente do caso de uso que o chamou — é assim que o Outbox Pattern
 * garante que o evento só existe se a escrita de negócio também existiu.
 * Um processo externo (Task de relay) lê essa tabela depois e publica no message broker.
 */
class EventDispatcher
{
    public static function publicar(string $evento, array $payload): void
    {
        $repository = new OutboxEventoRepository(Main::getConnection());
        $repository->save(new OutboxEvento($evento, $payload));
    }
}
