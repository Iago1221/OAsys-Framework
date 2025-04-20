<?php

namespace Framework\Infrastructure\DB\Migrations;

/**
 * Interface padrão para classes de atualização de databases.
 *
 * @since 16/03/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
interface IMigration
{
    /**
     * Executa as alterações pertinentes na base.
     * @return void
     */
    function up();

    /**
     * Desfaz as alterações realizadas na base.
     * @return void
     */
    function down();
}
