<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Interface\CalendarView;
use Framework\Infrastructure\MVC\View\Interface\GridView;
use Framework\Infrastructure\MVC\View\Interface\KanbanView;

abstract class CalendarController extends Controller
{
    protected CalendarView $view;

    public function show() {
        $this->getView()->setTitulo(Main::getOrder()->getTitle());
        $this->getView()->setRota(Main::getOrder()->getRoute());
        $this->beforeRender();
        $this->getView()->render();
    }

    protected function beforeRender() {}

    /**
     * Metodo deve ser chamado via AJAX para carregar as agendas do calendar
     * Utilização não obrigatória, é possível setar as agendas ao renderizar a view (de forma não dinâmica)
     *
     * @return void
     */
    public abstract function getSchedules(): void;

    /**
     * Metodo deve ser chamado via AAJX para carregar os eventos de determinada agenda
     * Utilização não obrigatória, é possível setar os eventos ao renderizar a view (de forma não dinâmica)
     *
     * @return void
     */
    public abstract function getEvents(): void;
}
