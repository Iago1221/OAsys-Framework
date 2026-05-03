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
    protected bool $hasFieldset = false;
    protected $fieldsetTitle = [];
    protected $fieldsetFields = [];
    protected $fieldsets = [];
    protected $afterAddRow;
    protected $afterRemoveRow;
    protected $exibeControls;
    private $lazyAddCall;
    private $flex;
    /** @var list<array{title: string, handler: string, iconHtml?: string}> */
    protected array $rowActionButtons = [];


    public function __construct(string $name, string $title)
    {
        $this->name = $name;
        $this->title = $title;
        $this->exibeControls = true;
        $this->flex = true;
    }

    public function disableControls(bool $disable = true)
    {
        $this->exibeControls = !$disable;
    }


    public function addFieldset($name, $title) {
        $this->fieldsets[] = $name;
        $this->fieldsetFields[$name] = [];
        $this->fieldsetTitle[] = $title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function setFlex(bool $flex): void
    {
        $this->flex = $flex;
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

    public function addFieldsetField($fieldsetName, IComponent $field): IComponent
    {
        $this->fieldsetFields[$fieldsetName][] = $field;
        return $field;
    }

    public function setFieldset(bool $hasFieldset = true): void
    {
        $this->hasFieldset = $hasFieldset;
    }

    public function afterAddRow($event)
    {
        $this->afterAddRow = $event;
    }

    public function afterRemoveRow($event)
    {
        $this->afterRemoveRow = $event;
    }

    public function bean(array $aData): void
    {
        $aNewFields = [];

        foreach ($this->fields as $oField) {
            if ($oField instanceof FormField) {
                $sField = $oField->getName();

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

    public function getName()
    {
        return $this->name;
    }

    /** Evento customizado que deverá ser chaamdo no JS para adicionar uma linha */
    public function setLazyAddCall(string $lazyAddCall)
    {
        $this->lazyAddCall = $lazyAddCall;
    }

    /**
     * Botão por linha do grid (ex.: ação rápida). O handler é uma função global (ex.: MinhaView.minhaFuncao);
     * o JS chama callFunctionByString(handler, indiceDaLinha).
     *
     * @param string $title Hint / acessibilidade (title do botão)
     * @param string $handler Nome qualificado da função JS
     * @param string|null $iconHtml HTML interno do botão (ícone); se vazio, usa "·"
     */
    public function addRowActionButton(string $title, string $handler, ?string $iconHtml = null): void
    {
        $this->rowActionButtons[] = [
            'title' => $title,
            'handler' => $handler,
            'iconHtml' => $iconHtml,
        ];
    }

    public function toArray(): array
    {
        if ($this->bDisabled) {
            foreach ($this->fields as $oField) {
                if ($oField instanceof FormComponent) {
                    $oField->setDisabled();
                }
            }

            foreach ($this->fieldsetFields as $aFields) {
                foreach ($aFields as $oField) {
                    if ($oField instanceof FormComponent) {
                        $oField->setDisabled();
                    }
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
                'lazyAddCall' => $this->lazyAddCall,
                'disabled' => $this->bDisabled,
                'exibeControls' => $this->exibeControls,
                'value' => $this->getValue(),
                'hasFieldset' => $this->hasFieldset,
                'fieldsetTitle' => $this->fieldsetTitle,
                'afterAddRow' => $this->afterAddRow,
                'afterRemoveRow' => $this->afterRemoveRow,
                'flex' => $this->flex,
                'fieldsets' => array_map(
                    fn($fieldset) => array_map(
                        fn($oField) => $oField->toArray(),
                        $this->fieldsetFields[$fieldset]
                    ),
                    $this->fieldsets
                ),
                'rowActionButtons' => $this->rowActionButtons,
            ]
        ];
    }
}
