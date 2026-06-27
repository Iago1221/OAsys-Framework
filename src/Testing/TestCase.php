<?php

namespace Framework\Testing;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Classe base para testes do OAsys.
 * Estende o TestCase do PHPUnit com helpers específicos do contexto do framework.
 */
abstract class TestCase extends PHPUnitTestCase
{
    /**
     * Cria um mock de repository que captura o último modelo passado para save().
     * Retorna [$mock, $spy] onde $spy->model contém o modelo capturado após a chamada.
     *
     * Uso:
     *   [$repo, $spy] = $this->spyOnSave(PedidoRepository::class);
     *   $service->executar($pedido);
     *   $this->assertEquals(Pedido::SITUACAO_PENDENTE, $spy->model->getSituacao());
     */
    protected function spyOnSave(string $repositoryClass): array
    {
        $spy = new \stdClass();
        $spy->model = null;
        $spy->calls = 0;

        $mock = $this->createMock($repositoryClass);
        $mock->method('save')
            ->willReturnCallback(function (object $model) use ($spy) {
                $spy->model = $model;
                $spy->calls++;
                return true;
            });

        return [$mock, $spy];
    }

    /**
     * Cria um mock de repository que captura TODOS os modelos passados para save().
     * Retorna [$mock, $spy] onde $spy->models é um array com todos os modelos capturados.
     *
     * Uso:
     *   [$repo, $spy] = $this->spyAllSaves(ItemRepository::class);
     *   $service->executar($pedido);
     *   $this->assertCount(3, $spy->models);
     */
    protected function spyAllSaves(string $repositoryClass): array
    {
        $spy = new \stdClass();
        $spy->models = [];

        $mock = $this->createMock($repositoryClass);
        $mock->method('save')
            ->willReturnCallback(function (object $model) use ($spy) {
                $spy->models[] = $model;
                return true;
            });

        return [$mock, $spy];
    }
}
