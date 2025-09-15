<?php

namespace Framework\Infrastructure\MVC\View\Components\Form;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class Button implements IComponent
{
    const TYPE_BUTTON = 'button',
          TYPE_SUBMIT = 'submit';
    private $name;
    private $title;
    private $type;
    private $onClickEvent;

    public function __construct($name, $title, $type = self::TYPE_BUTTON, $onClickEvent = null)
    {
        $this->name = $name;
        $this->title = $title;
        $this->type = $type;
        $this->onClickEvent = $onClickEvent;
    }

    public function setSubmit()
    {
        $this->type = self::TYPE_SUBMIT;
    }

    public function setButton()
    {
        $this->type = self::TYPE_BUTTON;
    }

    public function setOnClickEvent($onClickEvent)
    {
        $this->onClickEvent = $onClickEvent;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'title' => $this->title,
            'type' => $this->type,
            'onClickEvent' => $this->onClickEvent
        ];
    }
}
