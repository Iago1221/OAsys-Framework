<?php

namespace Framework\Core\Router;

use Framework\Infrastructure\Factory;
use Framework\Interface\Domain\Router\Order;

/**
 * Classe responsável por processar os pedidos gerados.
 *
 * @since 16/03/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class OrderProcessing
{

    /**
     * Processa o pedido recebido por parâmetro.
     * @param Order $oOrder
     * @return void
     */
    public function process(Order $oOrder)
    {
        $this->call($oOrder);
    }

    /**
     * Chama o metodo e classe de acordo com o pedido passado por parâmetro.
     * @param Order $order
     * @return void
     */
    private function call(Order $order)
    {
        (Factory::loadController($order->getClass()))->{$order->getMethod()}();
    }
}
