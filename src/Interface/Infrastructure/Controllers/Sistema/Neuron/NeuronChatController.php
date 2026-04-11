<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Neuron;

use ERP\Application\Neuron\EstoqueNeuronToolExecutor;
use ERP\Application\Neuron\NeuronLlmClient;
use ERP\Application\Neuron\NeuronOrchestrator;
use Framework\Core\Main;
use Framework\Infrastructure\MVC\Controller\Controller;
use Framework\Infrastructure\Response;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

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
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            Response::error('Use POST', 405);
        }

        $uid = Main::getUsuarioId();
        if ($uid === null) {
            Response::error('Não autenticado', 401);
        }

        /** @var \Framework\Interface\Domain\Usuario\Usuario|null $u */
        $u = $this->getRepository()->findBy('id', $uid);
        if ($u === null || !$u->getAcessoNeuron()) {
            Response::error('Sem acesso ao Oasys Neuron', 403);
        }

        $req = $this->getRequest();
        if (!is_array($req)) {
            Response::error('JSON inválido', 400);
        }

        $module = isset($req['module']) ? (string) $req['module'] : '';
        $messages = $req['messages'] ?? null;
        $clientContext = $req['clientContext'] ?? null;

        if ($module === '' || !is_array($messages)) {
            Response::error('Informe module e messages', 400);
        }

        if (!is_array($clientContext)) {
            $clientContext = null;
        }

        try {
            $llm = NeuronLlmClient::fromEnv();
            $tools = new EstoqueNeuronToolExecutor();
            $orch = new NeuronOrchestrator($llm, $tools);
            $result = $orch->run($module, $messages, $clientContext);
        } catch (\Throwable $e) {
            if (Main::isAmbienteDesenvolvimento()) {
                Response::error('Neuron: ' . $e->getMessage(), 500);
            }
            Response::error('Falha ao processar a conversa.', 500);
        }

        Response::success($result);
    }
}
