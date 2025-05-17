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
        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $property->setAccessible(true);
            $property->setValue($this, null);
        }
    }
}
