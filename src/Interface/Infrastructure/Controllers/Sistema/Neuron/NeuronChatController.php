<?php

namespace ERP\Infrastructure\Controllers\Sistema\Neuron;

use ERP\Application\Neuron\EstoqueNeuronToolExecutor;
use ERP\Application\Neuron\NeuronLlmClient;
use ERP\Application\Neuron\NeuronOrchestrator;
use Framework\Infrastructure\MVC\Controller\Controller;
use Framework\Infrastructure\Response;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

/**
 * Chat legado (LLM + tools ERP). A UI principal do Neuron usa o agente IPLNM via {@see NeuronAgentController}.
 */
class NeuronChatController extends Controller
{
    protected function getViewClass(): ?string
    {
        return null;
    }

    protected function getRepositoryClass(): string
    {
        return UsuarioRepository::class;
    }

    public function chat(): void
    {
        $raw = file_get_contents('php://input');
        if ($raw === false || $raw === '') {
            Response::error('Corpo da requisição vazio');

            return;
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            Response::error('JSON inválido');

            return;
        }

        $module = (string) ($data['module'] ?? '');
        $messages = $data['messages'] ?? null;
        if (!is_array($messages)) {
            Response::error('messages inválido');

            return;
        }

        $clientContext = $data['clientContext'] ?? null;
        $clientContext = is_array($clientContext) ? $clientContext : null;

        $orchestrator = new NeuronOrchestrator(
            NeuronLlmClient::fromEnv(),
            new EstoqueNeuronToolExecutor()
        );

        $out = $orchestrator->run($module, $messages, $clientContext);
        Response::success([
            'reply' => $out['reply'],
            'actions' => $out['actions'],
        ]);
    }
}
