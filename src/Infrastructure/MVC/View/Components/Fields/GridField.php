<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

class GridField extends Field
{
    protected ?string $hintIcon = 'clipboard-text';

    public function setHintIcon(string $hintIcon): self
    {
        $this->hintIcon = $hintIcon;
        return $this;
    }

    public function getHintIcon(): ?string
    {
        return $this->hintIcon;
    }
}
