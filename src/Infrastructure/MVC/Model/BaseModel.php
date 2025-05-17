<?php

namespace Framework\Infrastructure\MVC\Model;

abstract class BaseModel
{
    public function __construct()
    {
        $this->initializeProperties();
    }

    protected function initializeProperties(): void
    {
        foreach (get_object_vars($this) as $property => $value) {
            $this->$property = null;
        }
    }
}
