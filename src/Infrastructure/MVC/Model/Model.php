<?php

namespace Framework\Infrastructure\MVC\Model;

use JsonSerializable;

abstract class Model implements JsonSerializable
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

    protected function propertiesToSerializeIgnore(): array
    {
        return [];
    }

    public function jsonSerialize(): mixed
    {
        $data = [];
        $refClass = new \ReflectionClass($this);
        $ignorar = $this->propertiesToSerializeIgnore();

        foreach ($refClass->getProperties() as $prop) {
            $prop->setAccessible(true);
            $nome = $prop->getName();

            if (in_array($nome, $ignorar)) {
                continue;
            }

            $value = $prop->getValue($this);

            if (is_object($value)) {
                $value = $value->getId();
            }

            if (is_array($value)) {
                $jValue = [];
                if ($value[0] && is_object($value[0])) {
                    foreach ($value as $j) {
                        $jValue[] = $j->getId();
                    }

                    $value = $jValue;
                }
            }

            $data[$nome] = $value;
        }

        return $data;
    }
}
