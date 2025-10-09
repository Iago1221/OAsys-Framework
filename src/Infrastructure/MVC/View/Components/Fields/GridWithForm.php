<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class GridWithForm extends FormComponent
{
    protected string $name;
    protected string $title;
    protected string $layout = 'form-two-columns';
    protected array $fields = [];
    protected array $columns = [];
    protected $afterAddRow;
    protected $afterEditRow;
    protected $afterDeleteRow;

    public function __construct(string $name, string $title)
    {
        $this->name = $name;
        $this->title = $title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function addField(IComponent $field): IComponent
    {
        $this->fields[] = $field;
        return $field;
    }

    public function addColumn($name, $label)
    {
        $this->columns[$name] = $label;
    }

    public function afterAddRow($event)
    {
        $this->afterAddRow = $event;
    }

    public function afterEditRow($event)
    {
        $this->afterEditRow = $event;
    }

    public function afterDeleteRow($event)
    {
        $this->afterDeleteRow = $event;
    }

    public function bean(array $aData): void
    {

    }

    public function getName()
    {
        return $this->name;
    }

    public function toArray(): array
    {
        if ($this->bDisabled) {
            foreach ($this->fields as $oField) {
                if ($oField instanceof FormComponent) {
                    $oField->setDisabled();
                }
            }
        }

        return [
            'component' => 'GridWithFormComponent',
            'GridWithFormComponent' => [
                'name' => $this->name,
                'title' => $this->title,
                'layout' => $this->layout,
                'fields' => array_map(
                    fn($field) => $field->toArray(),
                    $this->fields
                ),
                'columns' => $this->columns,
                'afterAddRow' => $this->afterAddRow,
                'afterEditRow' => $this->afterEditRow,
                'afterDeleteRow' => $this->afterDeleteRow
            ]
        ];
    }
}
