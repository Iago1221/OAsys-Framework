<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class Tab extends FormComponent
{
    protected string $layout = 'form-two-columns';
    protected array $tabs = [];

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function addTab(string $title, ?string $layout = null): Tab
    {
        $tab = [
            'title' => $title,
            'layout' => $layout ?? $this->layout,
            'components' => []
        ];

        $this->tabs[] = $tab;
        return $this;
    }

    public function addComponent(IComponent $component): IComponent
    {
        if (empty($this->tabs)) {
            $this->addTab($this->title ?: 'Aba 1');
        }

        // Adiciona ao último tab criado
        $lastIndex = count($this->tabs) - 1;
        $this->tabs[$lastIndex]['components'][] = $component;
        return $component;
    }

    public function addComponentToTab(int $tabIndex, IComponent $component): IComponent
    {
        if (!isset($this->tabs[$tabIndex])) {
            throw new \InvalidArgumentException("Tab index $tabIndex does not exist");
        }

        $this->tabs[$tabIndex]['components'][] = $component;
        return $component;
    }

    public function bean(array $aData): void
    {
        foreach ($this->tabs as &$tab) {
            $aNewComponents = [];
            foreach ($tab['components'] as $oComponent) {
                if ($oComponent instanceof FormField) {
                    $sField = $oComponent->getName();

                    if (strpos($sField, '/') !== false) {
                        [$sParentKey, $sChildKey] = explode('/', $sField, 2);
                        if (isset($aData[$sParentKey][$sChildKey])) {
                            $oComponent->setValue($aData[$sParentKey][$sChildKey]);
                        }
                    } elseif (isset($aData[$sField])) {
                        $oComponent->setValue($aData[$sField]);
                    }

                    $aNewComponents[] = $oComponent;
                    continue;
                }

                $oComponent->bean($aData);
                $aNewComponents[] = $oComponent;
            }
            $tab['components'] = $aNewComponents;
        }
    }

    public function toArray(): array
    {
        if ($this->bDisabled) {
            foreach ($this->tabs as &$tab) {
                foreach ($tab['components'] as $oComponent) {
                    if ($oComponent instanceof FormComponent) {
                        $oComponent->setDisabled(true);
                    }
                }
            }
        }

        return [
            'component' => 'TabComponent',
            'TabComponent' => [
                'tabs' => array_map(function($tab) {
                    return [
                        'title' => $tab['title'],
                        'layout' => $tab['layout'],
                        'components' => array_map(
                            fn($component) => $component->toArray(),
                            $tab['components']
                        )
                    ];
                }, $this->tabs)
            ]
        ];
    }
}