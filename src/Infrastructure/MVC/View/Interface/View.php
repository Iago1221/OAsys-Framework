<?php

namespace Framework\Infrastructure\MVC\View\Interface;

abstract class View
{
    protected $oLayout;
    protected $aData = [];

    public function __construct($data = [])
    {
        $this->aData = $data;
        $this->create();
    }

    public function setLayout($oLayout)
    {
        $this->oLayout = $oLayout;
        return $this;
    }

    public function renderLayout()
    {
        return $this->oLayout->render($this->aData);
    }

    public abstract function render();
    protected abstract function create();
}
