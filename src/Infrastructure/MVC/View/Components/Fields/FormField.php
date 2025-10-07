<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;

class FormField extends Field
{
    protected bool $required;
    protected bool $renderLabel;

    public function __construct(string $field, string $label, string $type, bool $required = true, bool $disabled = false, mixed $value = null)
    {
        parent::__construct($field, $label, $type);
        $this->required = $required;
        $this->bDisabled = $disabled;
        $this->value = $value;
        $this->renderLabel = true;
    }

    public function setRenderLabel(bool $renderLabel = true): void
    {
        $this->renderLabel = $renderLabel;
    }

    public function toArray(): array
    {
        return [
            'component' => 'FormFieldComponent',
            'FormFieldComponent' => [
                'name' => $this->name,
                'label' => $this->label,
                'type' => $this->type,
                'options' => $this->options,
                'required' => $this->required,
                'disabled' => $this->bDisabled,
                'value' => $this->value,
                'renderLabel' => $this->renderLabel,
                'maxLength' => $this->maxLength,
                'decimalsLength' => $this->decimalsLength,
            ]
        ];
    }
}