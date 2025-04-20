<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Auth\General;

/**
 * Classe utilizada para setar as opções necessárias no arquivo index.
 */
class IndexController
{
    public static function setHeaders() {
        header('Access-Control-Allow-Origin: ' .  General::URL);
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, oasys-token');
        header('Access-Control-Allow-Credentials: false');
    }

    public static function setOptionsHeaders() {
        header('HTTP/1.1 200 OK');
        exit;
    }
}
