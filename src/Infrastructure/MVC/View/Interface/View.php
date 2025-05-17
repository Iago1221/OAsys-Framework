<?php

namespace Framework\Infrastructure\MVC\View\Interface;

use Framework\Infrastructure\MVC\View\Components\IComponent;

abstract class View
{
    protected IComponent $component;
    protected $titulo;
    protected $rota;
    protected $layout;
    protected $data = [];

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->instanciaComponent();
        $this->create();
    }

    abstract protected function instanciaComponent();

    protected function setComponent(IComponent $component)
    {
        $this->component = $component;
    }

    public function getComponent(): IComponent
    {
        return $this->component;
    }

    public function setLayout($oLayout)
    {
        $this->layout = $oLayout;
        return $this;
    }

    public function renderLayout()
    {
        return $this->layout->render($this->data);
    }

    public function setTitulo(string $titulo)
    {
        $this->titulo = $titulo;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setRota(string $rota)
    {
        $this->rota = $rota;
    }

    public function getRota()
    {
        return $this->rota;
    }

    public abstract function render();
    protected abstract function create();
}
