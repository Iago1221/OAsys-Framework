<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

class FormField extends Field
{
    protected bool $required;
    protected bool $renderLabel;
    protected ?string $onChange;

    public function __construct(string $field, string $label, string $type, bool $required = true, bool $disabled = false, mixed $value = null)
    {
        parent::__construct($field, $label, $type);
        $this->required = $required;
        $this->bDisabled = $disabled;
        $this->value = $value;
        $this->renderLabel = true;
        $this->onChange = null;
    }

    public function setRenderLabel(bool $renderLabel = true): void
    {
        $this->renderLabel = $renderLabel;
    }

    public function setOnChange(string $onChange): void
    {
        $this->onChange = $onChange;
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
                'onChange' => $this->onChange
            ]
        ];
    }
}