<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Core\Main;
use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Infrastructure\MVC\View\Interface\View;

/**
 * Abstração base para os controllers.
 * @author Iago Olivera <prog.iago.oliveira@gmail.com>
 */
abstract class Controller
{
    protected View $oView;
    protected Repository $oRepository;
    private ?array $request;

    public function __construct()
    {
        $this->setRequest();
        $this->oRepository = new ($this->getRepositoryClass())(Main::getConnection());
        $this->oView = new ($this->getViewClass())();
    }

    /**
     * Deve retornar uma instância da view que será manipulada pelo controller.
     * @return View::class
     */
    abstract protected function getViewClass(): string;

    /**
     * Deve ser utilizado para setar valor no atributo $oRepository.
     * @return Repository::class
     */
    abstract protected function getRepositoryClass(): string;

    /**
     * Retorna a instância da view definida para o controller.
     * @return View
     */
    protected function getView(): View
    {
        return $this->oView;
    }

    /**
     * Retorna a instância do repositório definido para o controller.
     * @return Repository
     */
    protected function getRepository(): Repository
    {
        return $this->oRepository;
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

    /**
     * Trata os dados recebidos na requisição.
     * @param $request
     * @return array|null
     */
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

    /**
     * Retorna os dados tratados a partir da requisição.
     * @param $data
     * @return mixed|null
     */
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

    /**
     * Define um novo parâmetro nos dados tratados a partir da requisição.
     * @param $sParamName
     * @param $xParamValue
     * @return void
     */
    protected function setParam($sParamName, $xParamValue)
    {
        $this->request[$sParamName] = $xParamValue;
    }

    protected function mapModelToArray($model) {
        $dados = $this->getRepository()->mapToArray($model);
        $fields = $dados[0];
        $values = $dados[1];
        $row = [];

        foreach ($fields as $i => $field) {
            $value = $values[$i];

            if (is_array($value)) {
                $arrayValue = [];
                foreach ($value as $jValue) {
                    if (is_object($jValue)) {
                        $arrayValue[] = $this->mapModelToArray($value);
                    } else {
                        $arrayValue[] = $jValue;
                    }
                }

                $row[$field] = $arrayValue;
            } else if (is_object($value)) {
                $row[$field] = $this->mapModelToArray($value);
            } else {
                $row[$field] = $value;
            }
        }

        return $row;
    }
}
