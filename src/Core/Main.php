<?php

namespace Framework\Core;

use Framework\Auth\Autenticator;
use Framework\Core\Router\OrderFactory;
use Framework\Core\Router\OrderProcessing;
use Framework\Infrastructure\DB\Persistence\Storage\PdoStorage;
use Framework\Infrastructure\Mensagem;
use Framework\Interface\Domain\Router\Order;
use Framework\Interface\Domain\Router\Rota;
use Framework\Interface\Infrastructure\Persistence\Core\RotaRepository;

/**
 * Classe principal.
 * Gerencia as requisições/retornos e a conexão com o banco de dados.
 *
 * @since 16/03/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class Main
{
    private static $dBConfig;
    private static $config;
    private static \PDO $connection;
    private static PdoStorage $pdoStorage;

    private static ?string $route;
    private static Order $order;
    private static ?int $usuarioId;

    public function __construct($route, RotaRepository $oRotaMapper)
    {
        self::$route = $route;
        $this->execute(new OrderFactory($oRotaMapper->findByRoute(self::$route)), $route);
    }

    protected function setUsuarioId($usuarioId)
    {
        self::$usuarioId = $usuarioId;
    }

    public static function getUsuarioId()
    {
        return self::$usuarioId;
    }

    public static function setBdConfig($aBDConfig)
    {
        if (!self::$dBConfig) {
            self::$dBConfig = $aBDConfig;
            return;
        }

        throw new \BadMethodCallException("Não é possível sobrescrever as configurações de banco de dados!");
    }

    public static function setConfig($config) {
        if (!self::$config) {
            self::$config = $config;
            return;
        }

        throw new \BadMethodCallException("Não é possível sobrescrever as configurações do ambiente!");
    }

    /**
     * Verifica se a rota passada por parâmetro é igual a rota requisitada.
     * @param $route
     * @return bool
     */
    public static function isRoute($route)
    {
        if (isset(self::$route)) {
            return self::$route == $route;
        }

        return false;
    }


    /**
     * Retorna o pedido gerado a partir da rota requisitada.
     * @return Order
     */
    public static function getOrder()
    {
        return self::$order;
    }

    /**
     * Reaiza o processamento da rota por meio de um pedido (Order).
     * @param OrderFactory $factory - Fábrica de pedido, instância o pedido de acordo com a rota.
     * @param $route - Rota requisitada.
     * @return void
     */
    private function execute(OrderFactory $factory, $route): void
    {
        try {
            self::$order = $factory->make();
            $oProcessing = new OrderProcessing();
            if (Autenticator::verifyToken()) {
                if (isset($_SESSION['usuario'])) {
                    if ($usuarioId = $_SESSION['usuario']) {
                        $this->setUsuarioId($usuarioId);
                    }

                    $oProcessing->process(self::$order);
                    return;
                }
            }

            if (isset($_SESSION['oasys-token']) || $route) {
                unset($_SESSION['oasys-token']);
                $this->setUnauthorizedReturn();
            }

            $factory->setRota(new Rota('sys_login', 'Core', 'LoginController', 'login', 'Login'));
            $oProcessing->process($factory->make());
        } catch (\Throwable $t) {
            if ($t instanceof Mensagem) {
                $this->setExceptionReturn($t->getMessage());
                return;
            }

            if (self::isAmbienteDesenvolvimento()) {
                $this->setExceptionReturn('Erro:' . $t->getMessage() . ' Arquivo: '  . $t->getFile() . 'Linha: ' . $t->getLine());
                return;
            }

            $this->setExceptionReturn('Houve um erro ao executar a ação, tente novamente mais tarde, ou contate o suporte.');
        }
    }

    public static function isAmbienteDesenvolvimento() {
        return self::$config['ambiente'] == 'DEV';
    }

    public static function isAmbienteQualidade() {
        return self::$config['ambiente'] == 'QA';
    }

    public static function isAmbienteProducao() {
        return self::$config['ambiente'] == 'PROD';
    }

    /**
     * Define o retorno caso o processamento da rota seja interrompido por falha na autenticação.
     * @return void
     */
    private function setUnauthorizedReturn()
    {
        http_response_code('401');
        header('Content-Type: application/json');
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }

    /**
     * Define o retorno caso ocorra uma exceção no processamento da rota.
     * @param $sMessage
     * @return void
     */
    private function setExceptionReturn($sMessage)
    {
        http_response_code('400');
        header('Content-Type: application/json');
        header("HTTP/1.1 400 Bad Request");
        exit($sMessage);
    }

    /**
     * Define o retorno caso o path requisitado não esteja disponível.
     * @param $sMessage
     * @return void
     */
    public static function setNotFoundException($sMessage)
    {
        http_response_code('404');
        header("HTTP/1.1 404 Not Found");
        exit($sMessage);
    }

    public static function switchConnection($dbConfig)
    {
        self::$dBConfig = $dbConfig;
        self::$connection = new \PDO(self::$dBConfig['dsn'], self::$dBConfig['user'], self::$dBConfig['password']);
    }

    /**
     * Retorna a instância do PDO conectado ao banco de dados.
     * @return \PDO
     */
    public static function getConnection()
    {
        if (!isset(self::$connection)) {
            self::$connection = new \PDO(self::$dBConfig['dsn'], self::$dBConfig['user'], self::$dBConfig['password']);
        }

        return self::$connection;
    }

    /**
     * Rwtorna a conexão com a storage gerenciada pelo PDO (PHP DATA OBJECT).
     * @return PdoStorage
     */
    public static function getPdoStorage()
    {
        if (!isset(self::$pdoStorage)) {
            self::$pdoStorage = new PdoStorage(self::getConnection());
        }

        return self::$pdoStorage;
    }
}
