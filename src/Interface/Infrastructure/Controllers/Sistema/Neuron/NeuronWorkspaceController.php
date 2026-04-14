<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Neuron;

use Framework\Infrastructure\MVC\Controller\Controller;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

/**
 * Abre a janela Oasys Neuron (web component) com rotas do agente IPLNM (proxy no ERP).
 */
class NeuronWorkspaceController extends Controller
{
    protected function getViewClass(): ?string
    {
        return null;
    }

    protected function getRepositoryClass(): string
    {
        return UsuarioRepository::class;
    }

    public function show(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'window' => [
                'title' => 'Oasys Neuron',
                'route' => 'sys_oasys_neuron',
                'width' => 'min(1180px, 96vw)',
                'fullscreen' => false,
            ],
            'component' => 'NeuronComponent',
            'NeuronComponent' => [
                'modules' => [
                    ['id' => 'estoque', 'label' => 'Estoque', 'enabled' => true],
                    ['id' => 'financeiro', 'label' => 'Financeiro', 'enabled' => false],
                    ['id' => 'vendas', 'label' => 'Vendas', 'enabled' => false],
                ],
                'agentRoute' => 'sys_oasys_neuron_agent',
                'intentsRoute' => 'sys_oasys_neuron_intents',
                'allowedOpenRoutes' => [
                    'sys_saldo_list',
                    'sys_estoque_report_list',
                    'sys_deposito_list',
                    'sys_produto_list',
                    'sys_estoque_relatorio_dinamico_def_list',
                ],
            ],
        ], JSON_UNESCAPED_UNICODE);
    }
}
