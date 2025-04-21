<?php

namespace Framework\Infrastructure;

class Factory
{
    public static function loadModel($module, $model, $dto)
    {
        $class = "ERP\Domain\\$module\\$model";
        return self::setModelValues(self::initializeModel(new $class()), $dto);
    }

    public static function loadController($sPacote, $controller)
    {
        $class = "ERP\Infrastructure\Controllers\\$controller";

        if ($sPacote == 'Sistema') {
            $class = "\Framework\Interface\Infrastructure\Controllers\\$controller";
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
