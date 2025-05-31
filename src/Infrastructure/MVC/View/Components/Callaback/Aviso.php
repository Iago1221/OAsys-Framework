<?php

namespace Framework\Infrastructure\MVC\View\Components\Callaback;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class Aviso implements IComponent
{
    const TIPO_SUCESSO = 'sucesso',
          TIPO_AVISO = 'aviso';
    protected $mensagem;
    protected $tipo;

    public function __construct($mensagem = null)
    {
        $this->setMensagem($mensagem);
        $this->setTipo(self::TIPO_SUCESSO);
    }

    public function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

    public function getMensagem() {
        return $this->mensagem;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function toArray(): array
    {
        return [
            'component' => 'AvisoComponent',
            'AvisoComponent' => [
                'mensagem' => $this->getMensagem(),
                'tipo' => $this->getTipo()
            ]
        ];
    }
}
