<?php

namespace Framework\Core\Router;

use Framework\Interface\Domain\Router\Order;
use Framework\Interface\Domain\Router\Rota;

/**
 * Fábrica de pedidos.
 * Classe utilizada para fabricar os pedidos (order) a serem processados a partir das rotas requisitadas.
 *
 * @since 16/03/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class OrderFactory
{
    private $oRota;

    public function __construct(Rota $oRota = null)
    {
        $this->oRota = $oRota;
    }

    public function setRota(Rota $oRota) {
        $this->oRota = $oRota;
    }
    /**
     * Fabrica o pedido de acordo com a rota passada por parâmetro.
     * @param $sRoute
     * @return Order
     */
    public function make(): Order
    {
        if (!$this->oRota) {
            return new Order(null,  'Core', 'IndexController', 'index', 'Index');
        }

        return new Order($this->oRota->getNome(), $this->oRota->getPacote(), $this->oRota->getCaminho(), $this->oRota->getMetodo(), $this->oRota->getTitulo());
    }
}
