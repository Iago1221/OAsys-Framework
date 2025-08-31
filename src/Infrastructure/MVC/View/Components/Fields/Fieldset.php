<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

use Framework\Infrastructure\MVC\View\Components\Fields\FormComponent;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Infrastructure\MVC\View\Components\IComponent;

class Fieldset extends FormComponent
{
    protected string $layout = 'form-two-columns';
    protected string $title;
    protected array $components = [];
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function addComponent(IComponent $component): IComponent
    {
        $this->components[] = $component;
        return $component;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function bean($aData)
    {
        $aComponents = $this->components;
        $aNewComponents = [];

        foreach ($aComponents as $oComponent) {
            if ($oComponent instanceof FormField) {
                $sField = $oComponent->getName();

                if (strpos($sField, '/') !== false) {
                    $parts = explode('/', $sField);
                    $sParentKey = $parts[0];
                    $sChildKey = $parts[1];

                    if (isset($aData[$sParentKey]) && is_array($aData[$sParentKey])) {
                        $aNested = $aData[$sParentKey];
                        if ($aNested[$sChildKey]) {
                            $oComponent->setValue($aNested[$sChildKey]);
                        }
                    }
                } else {
                    if (isset($aData[$sField])) {
                        $oComponent->setValue($aData[$sField]);
                    }
                }

                $aNewComponents[] = $oComponent;
                continue;
            }

            $oComponent->bean($aData);
            $aNewComponents[] = $oComponent;
        }

        $this->setComponents($aNewComponents);
    }

    public function setComponents(array $aComponents): void
    {
        $this->components = $aComponents;
    }

    public function toArray(): array
    {
        if ($this->bDisabled) {
            /** @var FormComponent $oComponent */
            foreach ($this->components as $oComponent) {
                $oComponent->setDisabled();
            }
        }

        return [
            'component' => 'FieldsetComponent',
            'FieldsetComponent' => [
                'title' => $this->title,
                'name' => $this->name,
                'layout' => $this->layout,
                'components' => array_map(fn($component) => $component->toArray(), $this->components),
            ]
        ];
    }
}
