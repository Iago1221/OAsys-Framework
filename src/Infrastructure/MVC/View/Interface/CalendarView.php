<?php

namespace Framework\Infrastructure\MVC\View\Interface;

use Framework\Infrastructure\MVC\View\Components\Calendar\Calendar;
use Framework\Infrastructure\MVC\View\Components\IComponent;

abstract class CalendarView extends View
{
    protected function instanciaViewComponent()
    {
        $this->setViewComponent(new Calendar());
    }

    /** @return Calendar */
    public function getViewComponent(): IComponent
    {
        return parent::getViewComponent();
    }

    public function render($aData = [])
    {
        $window = [
            'window' => [
                'title' => $this->getTitulo(),
                'route' => $this->getRota()
            ]
        ];

        $component = array_merge($window, $this->getViewComponent()->toArray());

        echo json_encode($component);
    }
}
