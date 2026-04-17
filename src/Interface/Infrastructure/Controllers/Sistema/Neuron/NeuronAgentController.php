<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Neuron;

use Framework\Infrastructure\MVC\Controller\Controller;
use Framework\Infrastructure\Response;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

/**
 * Proxy para o serviço do agente de estoque (IPLNM): POST /v1/erp/agent e GET /v1/erp/intents.
 */
abstract class NeuronAgentController extends Controller
{
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
        $this->call('GET', $this->intentsUrl(), null);
    }

    public function invoke(): void
    {
        $data = $this->getRequest();
        if ($data === false || $data === '') {
            Response::error('Corpo da requisição vazio');
        }

        $intent = $data['intent'] ?? null;
        if (!is_string($intent) || $intent === '') {
            Response::error('Campo intent é obrigatório');
        }

        $this->call('POST', $this->agentUrl(), $data);
    }

    protected abstract function intentsUrl(): string;
    protected abstract function agentUrl(): string;

    private function call(string $method, string $url, ?string $postBody): void
    {
        $ch = curl_init($url);
        if ($ch === false) {
            Response::error('Falha ao contatar o agente de estoque');
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
        }

        $parsed = json_decode((string) $raw, true);
        if (!is_array($parsed)) {
            Response::error('Resposta inválida do agente (HTTP ' . $status . ')');
        }

        if ($status >= 400) {
            $detail = $parsed['detail'] ?? $raw;
            if (!is_string($detail)) {
                $detail = json_encode($parsed, JSON_UNESCAPED_UNICODE);
            }
            Response::error($detail);
        }

        Response::success($parsed);
    }
}
