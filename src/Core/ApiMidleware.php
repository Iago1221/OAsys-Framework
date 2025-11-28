<?php

namespace Framework\Core;

use Framework\Auth\Autenticator;
use Framework\Infrastructure\Factory;
use Framework\Infrastructure\MVC\Controller\ApiController;
use Framework\Infrastructure\Response;
use Framework\Interface\Infrastructure\Controllers\Core\LoginController;

class ApiMidleware
{
    protected $api;

    public function __construct($api)
    {
        $this->api = $api;
        if ($this->api == 'login') {
            $this->login();
        }
    }

    public function login()
    {
        (new LoginController())->loginApi();
    }

    public function call($htpp_method, $calledController, $pathParams)
    {
        if (Autenticator::verifyApiToken()) {
            $controllerClass = $this->api . 'Controller';
            /** @var ApiController $apiController */
            $apiController = Factory::loadController('API', $controllerClass);
            $apiController->setHttpMethod($htpp_method);
            $apiController->execute($calledController, $pathParams);
            return;
        }

        Response::error('Unauthorized', 401);
    }
}
