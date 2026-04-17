<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Neuron;

use Framework\Infrastructure\MVC\Controller\Controller;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;
use Framework\Interface\Infrastructure\View\Sistema\Neuron\NeuronWorkspaceView;

/**
 * Abre a janela Oasys Neuron (web component) com rotas do agente IPLNM (proxy no ERP).
 */
class NeuronWorkspaceController extends Controller
{
    protected function getViewClass(): ?string
    {
        return NeuronWorkspaceView::class;
    }

    protected function getRepositoryClass(): string
    {
        return UsuarioRepository::class;
    }
}
