<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

use Framework\Infrastructure\MVC\View\Components\IComponent;

/**
 * Componente de filtro utilizado no componente 'Grid'.
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 * @since 17/05/2025
 */
class GridFilter extends Field
{
    private $operator;

    public static function fromGridField(GridField $gridField)
    {
        return new self($gridField->getName(), $gridField->getLabel(), $gridField->getType());
    }

    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    public function getOperator()
    {
        return $this->operator;
    }

    public function toArray(): array
    {
        return [
            'component' => 'GridFilterComponent',
            'GridFilterComponent' => [
                'name' => $this->getName(),
                'label' => $this->getLabel(),
                'type' => $this->getType(),
                'options' => $this->getOptions(),
                'operator' => $this->getOperator(),
                'value' => $this->getValue(),
            ]
        ];
    }
}