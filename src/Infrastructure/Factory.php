<?php

namespace Framework\Infrastructure;

class Factory
{
    public static function loadModel($module, $model, $dto)
    {
        $class = "\src\Sistema\Domain\\$module\\$model";
        return self::setModelValues(self::initializeModel(new $class()), $dto);
    }

    public static function loadController($controller)
    {
        $class = "\src\OASYS\Interface\Infrastructure\Controllers\\$controller";

        if (file_exists("\src\Sistema\Infrastructure\Controllers\\$controller")) {
            $class = "\src\Sistema\Infrastructure\Controllers\\$controller";
        }

        return new $class();
    }

    public static function initializeModel($model)
    {
        $oReflection = new \ReflectionClass($model);
        foreach ($oReflection->getProperties() as $property) {
            $property->setAccessible(true);
            $property->setValue($model, null);
        }

        return $model;
    }

    public static function setModelValues($model, $dto)
    {
        foreach ($dto as $key => $value) {
            $method = "set" . ucfirst($key);
            $model->{$method}($value);
        }

        return $model;
    }
}
