<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

abstract class Field extends FormComponent
{
    const TYPE_NUMBER   = 'number';
    const TYPE_TEXT     = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_DATE     = 'date';
    const TYPE_LIST     = 'list';
    const TYPE_CHECK    = 'checkbox';
    const TYPE_PASSWORD = 'password';
    const TYPE_EMAIL    = 'email';
    const TYPE_FILE     = 'file';
    const TYPE_SUGGEST  = 'suggest';

    protected string $name;
    protected string $label;
    protected string $type;
    protected mixed $value;
    protected array $options = [];

    public function __construct(string $name, string $label, string $type)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function addOption($value, $description): void
    {
        $this->options[$value] = $description;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'component' => 'FieldComponent',
            'FieldComponent' => [
                'name' => $this->name,
                'label' => $this->label,
                'type' => $this->type,
                'options' => $this->options,
            ]
        ];
    }
}
