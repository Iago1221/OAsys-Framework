<?php

namespace Framework\Infrastructure\MVC\View\Components\Fields;

class GridField extends Field
{
    protected ?string $hintIcon = 'clipboard-text';
    protected bool $sortable = true;

    public function setHintIcon(string $hintIcon): self
    {
        $this->hintIcon = $hintIcon;
        return $this;
    }

    public function getHintIcon(): ?string
    {
        return $this->hintIcon;
    }

    public function setSortable(bool $sortable): self
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function isSortable(): bool
    {
        if (str_contains($this->getName(), '/')) {
            return false;
        }
        return $this->sortable;
    }
}
