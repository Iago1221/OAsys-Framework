<?php

namespace Framework\Infrastructure\MVC\Model;

abstract class ReadModel extends Model
{
    public function canPersist(): bool
    {
        return false;
    }
}
