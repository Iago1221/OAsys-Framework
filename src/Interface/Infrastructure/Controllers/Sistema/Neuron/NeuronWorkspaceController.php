<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Neuron;

use ERP\Application\Neuron\EstoqueNeuronToolExecutor;
use Framework\Core\Main;
use Framework\Infrastructure\MVC\Controller\Controller;
use Framework\Infrastructure\Response;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

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
        $this->assertNeuronAccess();

        $allowedOpenRoutes = array_column(EstoqueNeuronToolExecutor::allowedOpenRoutesList(), 'route');

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'window' => [
                'title' => 'Oasys Neuron',
                'route' => 'sys_oasys_neuron',
                'width' => 'min(1100px, 98vw)',
                'fullscreen' => true,
            ],
            'component' => 'NeuronComponent',
            'NeuronComponent' => [
                'chatRoute' => 'sys_oasys_neuron_chat',
                'modules' => [
                    ['id' => 'estoque', 'label' => 'Estoque', 'enabled' => true],
                    ['id' => 'vendas', 'label' => 'Vendas', 'enabled' => false],
                    ['id' => 'financeiro', 'label' => 'Financeiro', 'enabled' => false],
                ],
                'allowedOpenRoutes' => $allowedOpenRoutes,
            ],
        ], JSON_UNESCAPED_UNICODE);
    }

    private function assertNeuronAccess(): void
    {
        $uid = Main::getUsuarioId();
        if ($uid === null) {
            Response::error('Não autenticado', 401);
        }

        /** @var \Framework\Interface\Domain\Usuario\Usuario|null $u */
        $u = $this->getRepository()->findBy('id', $uid);
        if ($u === null || !$u->getAcessoNeuron()) {
            Response::error('Sem acesso ao Oasys Neuron', 403);
        }
    }
}
