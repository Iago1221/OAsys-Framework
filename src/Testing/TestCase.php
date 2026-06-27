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
     * Retorna [$mock, &$modelCapturado].
     *
     * Uso:
     *   [$repo, $salvo] = $this->spyOnSave(PedidoRepository::class);
     *   $service->executar($pedido);
     *   $this->assertEquals(Pedido::SITUACAO_PENDENTE, $salvo->getSituacao());
     */
    protected function spyOnSave(string $repositoryClass): array
    {
        $captured = null;
        $mock = $this->createMock($repositoryClass);
        $mock->method('save')
            ->willReturnCallback(function (object $model) use (&$captured) {
                $captured = $model;
                return true;
            });
        return [$mock, &$captured];
    }

    /**
     * Cria um mock de repository que captura TODOS os modelos passados para save().
     * Retorna [$mock, &$listaCapturada].
     *
     * Uso:
     *   [$repo, $salvos] = $this->spyAllSaves(ItemRepository::class);
     *   $service->executar($pedido);
     *   $this->assertCount(3, $salvos);
     */
    protected function spyAllSaves(string $repositoryClass): array
    {
        $captured = [];
        $mock = $this->createMock($repositoryClass);
        $mock->method('save')
            ->willReturnCallback(function (object $model) use (&$captured) {
                $captured[] = $model;
                return true;
            });
        return [$mock, &$captured];
    }

    /**
     * Asserta que save() foi chamado exatamente uma vez no mock de repository.
     */
    protected function assertSavedOnce(string $repositoryClass): object
    {
        $mock = $this->createMock($repositoryClass);
        $mock->expects($this->once())->method('save');
        return $mock;
    }

    /**
     * Asserta que save() NUNCA foi chamado no mock de repository.
     */
    protected function assertNeverSaved(string $repositoryClass): object
    {
        $mock = $this->createMock($repositoryClass);
        $mock->expects($this->never())->method('save');
        return $mock;
    }
}
