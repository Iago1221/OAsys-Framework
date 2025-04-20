<?php

namespace Framework\Infrastructure\DB\Migrations;

use Framework\Infrastructure\DB\Persistence\Storage\GenericStorage;

/**
 * Abstração das classes de atualização das databases.
 * Deve ser estentidada por todas classes de atualização (Migration).
 *
 * @since 16/03/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
abstract class StorageMigration implements IMigration
{
    /** @var GenericStorage */
    protected $oStorage;

    public function __construct(GenericStorage $oStorage)
    {
        $this->oStorage = $oStorage;
    }
}
