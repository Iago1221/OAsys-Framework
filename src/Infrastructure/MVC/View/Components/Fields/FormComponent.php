<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

use Framework\Infrastructure\MVC\View\Components\IComponent;

abstract class FormComponent implements IComponent
{
    protected $bDisabled;

    public function setDisabled(bool $bDisabled = true): void
    {
        $this->bDisabled = $bDisabled;
    }
}
