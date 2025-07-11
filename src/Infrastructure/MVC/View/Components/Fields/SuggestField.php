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
        $sFieldName = str_replace($this->name . "/", "", $this->field->getName());
        $sDescriptionFieldName = str_replace($this->name . "/", "", $this->descriptionField->getName());

        $data = $this->geTreatedData($aData);

        if (is_array($data)) {
            $this->field->setValue(isset($data[$sFieldName]) ? $data[$sFieldName] : null);
            $this->descriptionField->setValue(isset($data[$sDescriptionFieldName]) ? $data[$sDescriptionFieldName] : null);
        }
    }

    protected function geTreatedData($data)
    {
        if (strpos($this->name, '/') === false) {
            if (isset($data[$this->name])) {
                return $data[$this->name];
            }

            return null;
        }

        $parts = explode('/', $this->name);

        foreach ($parts as $part) {
            if (is_array($data) && array_key_exists($part, $data)) {
                if (isset($data[$part])) {
                    $data = $data[$part];
                }
            }
        }

        return $data;
    }

    public function toArray(): array
    {
        return [
            'component' => 'SuggestFieldComponent',
            'SuggestFieldComponent' => [
                'name' => $this->name,
                'idField' => $this->field->toArray(),
                'descriptionField' => $this->descriptionField->toArray(),
                'label' => $this->label,
                'disabled' => $this->bDisabled,
                'route' => $this->sRoute,
            ]
        ];
    }
}
