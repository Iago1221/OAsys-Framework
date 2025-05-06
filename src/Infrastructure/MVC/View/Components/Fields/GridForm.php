<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class GridForm extends FormComponent
{
    protected string $name;
    protected string $title;
    protected string $layout = 'form-two-columns';
    protected array $fields = [];
    protected int $rows = 1;
    protected int $maxRows = 10;
    protected array $aValue = [];

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

    public function setMaxRows(int $maxRows): void
    {
        $this->maxRows = $maxRows;
    }

    public function addField(IComponent $field): IComponent
    {
        $this->fields[] = $field;
        return $field;
    }

    public function bean(array $aData): void
    {
        $aNewFields = [];

        foreach ($this->fields as $oField) {
            if ($oField instanceof FormField) {
                $sField = $oField->getField();

                if (isset($aData[$sField]) && is_array($aData[$sField])) {
                    $this->rows = count($aData[$sField]);

                    if (isset($aData[$sField][0])) {
                        $oField->setValue($aData[$sField][0]);
                    }
                }

                $aNewFields[] = $oField;
                continue;
            }

            $oField->bean($aData);
            $aNewFields[] = $oField;
        }

        $this->fields = $aNewFields;
    }

    public function setValue($aRows)
    {
        $this->aValue = $aRows;
    }

    public function getValue()
    {
        return $this->aValue;
    }

    public function toArray(): array
    {
        if ($this->bDisabled) {
            foreach ($this->fields as $oField) {
                if ($oField instanceof FormComponent) {
                    $oField->setDisabled(true);
                }
            }
        }

        return [
            'component' => 'GridFormComponent',
            'GridFormComponent' => [
                'name' => $this->name,
                'title' => $this->title,
                'layout' => $this->layout,
                'fields' => array_map(
                    fn($field) => $field->toArray(),
                    $this->fields
                ),
                'rows' => $this->rows,
                'maxRows' => $this->maxRows,
                'value' => $this->getValue()
            ]
        ];
    }
}