<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Infrastructure\DB\Persistence\Storage\Mapper\GenericMapper;
use Framework\Infrastructure\MVC\View\Interface\View;

/**
 * Abstração base para os controllers.
 */
abstract class Controller
{
    protected $oView;
    protected $oMapper;
    private $request;

    public function __construct()
    {
        $this->setRequest();
        $this->setMapper();
        $this->oView = $this->getView();
    }

    /**
     * Deve retornar uma instância da view que será manipulada pelo controller.
     * @return View
     */
    public abstract function getView(): View;

    /**
     * Deve ser utilizado para setar valor no atributo $oMapper.
     * @return void
     */
    abstract protected function setMapper(): void;

    /**
     * Retornar o mapeador a ser utilizado no controle das requisições.
     * @return GenericMapper
     */
    public function getMapper(): GenericMapper
    {
        return $this->oMapper;
    }

    /**
     * Atribui o payload recebido ao atributo $request.
     * @return void
     */
    protected function setRequest() {
        if ($_POST) {
            $this->request = $this->trataRequest($_POST);
            return;
        }

        $request = file_get_contents('php://input');
        $requestData = $this->trataRequest(json_decode($request, true));
        $this->request = $requestData;
    }

    protected function trataRequest($request)
    {
        if (!$request) {
            return null;
        }

        foreach ($request as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if (is_string($value2) && !$value2) {
                        $request[$key][$key2] = null;
                    }
                }
            } else {
                if (is_string($value) && !$value) {
                    $request[$key] = null;
                }
            }
        }

        return $request;
    }

    protected function getRequest($data = null)
    {
        if ($data) {
            if (isset($this->request[$data])) {
                return $this->request[$data];
            }

            return null;
        }

        return $this->request;
    }

    protected function setParam($sParamName, $xParamValue)
    {
        $this->request[$sParamName] = $xParamValue;
    }
}
