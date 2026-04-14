<?php

namespace ERP\Infrastructure\Controllers\Sistema\Neuron;

use Framework\Infrastructure\MVC\Controller\Controller;
use Framework\Infrastructure\Response;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

/**
 * Proxy para o serviço do agente de estoque (IPLNM): POST /v1/erp/agent e GET /v1/erp/intents.
 */
class NeuronAgentController extends Controller
{
    private const DEFAULT_AGENT_BASE = 'http://127.0.0.1:8000';

    protected function getViewClass(): ?string
    {
        return null;
    }

    protected function getRepositoryClass(): string
    {
        return UsuarioRepository::class;
    }

    public function intents(): void
    {
        $base = $this->agentBaseUrl();
        $url = rtrim($base, '/') . '/v1/erp/intents';
        $this->proxyJson('GET', $url, null);
    }

    public function invoke(): void
    {
        $raw = file_get_contents('php://input');
        if ($raw === false || $raw === '') {
            Response::error('Corpo da requisição vazio');

            return;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            Response::error('JSON inválido');

            return;
        }

        $intent = $decoded['intent'] ?? null;
        if (!is_string($intent) || $intent === '') {
            Response::error('Campo intent é obrigatório');

            return;
        }

        $base = $this->agentBaseUrl();
        $url = rtrim($base, '/') . '/v1/erp/agent';
        $this->proxyJson('POST', $url, $raw);
    }

    private function agentBaseUrl(): string
    {
        $env = $_ENV['OASYS_NEURON_AGENT_URL'] ?? getenv('OASYS_NEURON_AGENT_URL');

        return is_string($env) && $env !== '' ? $env : self::DEFAULT_AGENT_BASE;
    }

    private function proxyJson(string $method, string $url, ?string $postBody): void
    {
        $ch = curl_init($url);
        if ($ch === false) {
            Response::error('Falha ao contatar o agente de estoque');

            return;
        }

        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ];

        if ($method === 'POST') {
            $opts[CURLOPT_POST] = true;
            $opts[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
            $opts[CURLOPT_POSTFIELDS] = $postBody;
        }

        curl_setopt_array($ch, $opts);
        $raw = curl_exec($ch);
        $errno = curl_errno($ch);
        $err = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errno !== 0) {
            Response::error('Agente indisponível: ' . $err);

            return;
        }

        $parsed = json_decode((string) $raw, true);
        if (!is_array($parsed)) {
            Response::error('Resposta inválida do agente (HTTP ' . $status . ')');

            return;
        }

        if ($status >= 400) {
            $detail = $parsed['detail'] ?? $raw;
            if (!is_string($detail)) {
                $detail = json_encode($parsed, JSON_UNESCAPED_UNICODE);
            }
            Response::error($detail);

            return;
        }

        Response::success($parsed);
    }
}
