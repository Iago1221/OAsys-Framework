<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Components\Fields\GridField;
use Framework\Infrastructure\MVC\View\Interface\GridView;
use Framework\Infrastructure\MVC\View\Interface\KanbanView;

abstract class KanbanController extends Controller
{
    protected KanbanView $view;

    public function show() {
        $this->getView()->setTitulo(Main::getOrder()->getTitle());
        $this->getView()->setRota(Main::getOrder()->getRoute());
        $this->beforeRender();
        $this->getView()->render();
    }

    protected function beforeRender() {}

    public abstract function getItens();
}
