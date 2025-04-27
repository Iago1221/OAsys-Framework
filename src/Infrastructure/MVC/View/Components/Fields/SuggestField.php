<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

use Framework\Infrastructure\MVC\View\Components\Fields\Field;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;

class SuggestField extends Field
{
    private FormField $field;
    private FormField $descriptionField;
    private $sRoute;

    public function __construct(string $sName, FormField $field, FormField $descriptionField, string $label, string $sRoute)
    {
        parent::__construct($sName, $label, Field::TYPE_SUGGEST);
        $this->sRoute = $sRoute;
        $this->field = $field;
        $this->descriptionField = $descriptionField;

        $this->field->setRenderLabel(false);
        $this->descriptionField->setRenderLabel(false);
    }

    public function bean($aData)
    {
        $sFieldName = str_replace($this->sField . "/", "", $this->field->getField());
        $sDescriptionFieldName = str_replace($this->sField . "/", "", $this->descriptionField->getField());

        $this->field->setValue($aData[$this->sField][$sFieldName]);
        $this->descriptionField->setValue($aData[$this->sField][$sDescriptionFieldName]);
    }

    public function toArray(): array
    {
        return [
            'component' => 'SuggestFieldComponent',
            'SuggestFieldComponent' => [
                'name' => $this->sField,
                'idField' => $this->field->toArray(),
                'descriptionField' => $this->descriptionField->toArray(),
                'label' => $this->label,
                'disabled' => $this->bDisabled,
                'route' => $this->sRoute,
            ]
        ];
    }
}
