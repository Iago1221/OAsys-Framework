<?php

namespace Framework\Interface\Infrastructure\View\Sistema\Neuron;

use Framework\Infrastructure\MVC\View\Components\Neuron\NeuronModule;
use Framework\Infrastructure\MVC\View\Components\Neuron\NeuronWorkspace;
use Framework\Infrastructure\MVC\View\Interface\View;

class NeuronWorkspaceView extends View
{
    /** @var NeuronModule[] */
    protected static array $modules;

    protected function instanciaViewComponent()
    {
        $this->setViewComponent(new NeuronWorkspace());
    }

    public function render()
    {
        echo json_encode($this->getViewComponent()->toArray());
    }

    public static function addModule(NeuronModule $module)
    {
        self::$modules[] = $module;
    }

    protected function create()
    {
        foreach (self::$modules as $module) {
            $this->getViewComponent()->addModule($module);
        }
    }
}
