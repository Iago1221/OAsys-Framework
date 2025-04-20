<?php

namespace Framework\Infrastructure\DB\Persistence\Storage;


use Framework\Infrastructure\DB\Persistence\Storage\IStorage;

/**
 * @since 24/02/2025
 * @author Iago Oliveira <iago.oliveira@gmail.com>
 */
abstract class GenericStorage implements IStorage
{
    /** @var string */
    protected $sFrom;

    public function from(string $sFrom): void
    {
        $this->sFrom = $sFrom;
    }
}
