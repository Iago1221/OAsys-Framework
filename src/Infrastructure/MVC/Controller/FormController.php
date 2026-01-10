<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Core\Main;
use Framework\Infrastructure\Mensagem;
use Framework\Infrastructure\MVC\Model\StatusModel;
use Framework\Infrastructure\MVC\View\Components\Callaback\Aviso;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;
use Framework\Interface\Domain\Log\Log;
use Framework\Interface\Infrastructure\Persistence\Sistema\Log\LogRepository;

abstract class FormController extends Controller
{
    protected $model = null;
    protected bool $gravaLog = true;
    protected $controlaPersistencia = false;
    protected ?string $title = null;
    protected ?string $route = null;

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    public function show($bDisabled = true, $instanciaModel = true)
    {
        if ($instanciaModel) {
            $this->instanciaModelById(isset($_GET['id']) ? $_GET['id'] : null);
        }

        $this->getView()->setTitulo($this->title ?? Main::getOrder()->getTitle());
        $this->getView()->setRota($this->route ?? Main::getOrder()->getRoute());

        $aData['bDisabled'] = $bDisabled;

        $this->beforeRender($this->getModel(), $aData);

        if ($this->getModel()) {
            $this->formBean($this->getModel());
        }

        $this->getView()->render($aData);
    }

    protected function instanciaModelById($id)
    {
        if ($id) {
            return $this->setModel($this->getRepository()->findBy('id', $id));
        }

        return null;
    }

    protected function setModel($model){
        $this->model = $model;
    }

    protected function getModel()
    {
        return $this->model;
    }

    protected function beforeRender($oModel, &$aData) {}

    protected function formBean($oModel)
    {
        if (is_object($oModel)) {
            $aData = $this->mapModelToArray($oModel);
        }

        $aComponents = $this->getView()->getComponents();

        foreach ($aComponents as $oComponent) {
            if ($oComponent instanceof FormField) {
                $sField = $oComponent->getName();

                if (strpos($sField, '/') !== false) {
                    $parts = explode('/', $sField);
                    $sParentKey = $parts[0];
                    $sChildKey = $parts[1];

                    if (isset($aData[$sParentKey]) && is_array($aData[$sParentKey])) {
                        $aNested = $aData[$sParentKey];
                        if ($aNested[$sChildKey]) {
                            $oComponent->setValue($aNested[$sChildKey]);
                        }
                    }
                } else {
                    if (isset($aData[$sField])) {
                        $oComponent->setValue($aData[$sField]);
                    }
                }

                continue;
            }

            $oComponent->bean($aData);
        }
    }

    /** @return void|null */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->show(false);
            return;
        }

        try {
            Main::getPdoStorage()->beginTransaction();
            $this->getRepository()->setControlaTransacao(false);
            $oModel = $this->getRepository()->mapToModel($this->getRequest());

            if ($this->controlaPersistencia) {
                $this->addFunction($oModel);
            } else {
                $this->beforeAdd($oModel);
                $this->getRepository()->saveWithRelations($oModel);
                $this->afterAdd($oModel);
            }


            Main::getPdoStorage()->commit();
            $this->setAvisoRetorno('Registro incluído com sucesso!');
        } catch (\Throwable $t) {
            Main::getPdoStorage()->rollback();
            throw $t;
        }
    }

    public function addFunction($model) {}
    public function editFunction($model) {}
    public function deleteFunction($model) {}

    /** @return LogRepository */
    protected function getLogRepository()
    {
        return new LogRepository(Main::getConnection());
    }

    protected function beforeAdd($model) {}
    protected function afterAdd($model) {
        if ($this->gravaLog) {
            $log = Log::comRotaUsuarioEDados($this->route ?? Main::getOrder()->getRoute(), Main::getUsuarioId(), json_encode($model));
            $this->getLogRepository()->save($log);
        }
    }

    protected function beforeEdit($model) {}

    protected function afterEdit($model) {
        if ($this->gravaLog) {
            $log = Log::comRotaUsuarioEDados($this->route ?? Main::getOrder()->getRoute(), Main::getUsuarioId(), json_encode($model));
            $this->getLogRepository()->save($log);
        }
    }

    protected function beforeDelete($model) {}
    protected function afterDelete($model) {
        if ($this->gravaLog) {
            $log = Log::comRotaUsuarioEDados($this->route ?? Main::getOrder()->getRoute(), Main::getUsuarioId());
            $this->getLogRepository()->save($log);
        }
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->show(false);
            return;
        }

        try {
            $aData = $this->getRequest();
            Main::getPdoStorage()->beginTransaction();

            $this->getRepository()->setControlaTransacao(false);
            $oModel = $this->getRepository()->findBy('id', $aData['id']);
            $oModel = $this->bean($oModel, $aData);

            if ($this->controlaPersistencia) {
                $this->editFunction($oModel);
            } else {
                $this->beforeEdit($oModel);
                $this->getRepository()->saveWithRelations($oModel);
                $this->afterEdit($oModel);
            }

            Main::getPdoStorage()->commit();
            $this->setAvisoRetorno('Registro alterado com sucesso!');
        } catch (\Throwable $t) {
            Main::getPdoStorage()->rollback();
            throw $t;
        }
    }

    protected function bean($oModel, $aData)
    {
        $oReflection = new \ReflectionClass($oModel);

        foreach ($aData as $aField => $aValue) {
            if ($oReflection->hasProperty($aField)) {
                $oReflectionProperty = $oReflection->getProperty($aField);
                $oReflectionProperty->setAccessible(true);

                if (!is_array($aValue)) {
                    $oReflectionProperty->setValue($oModel, $aValue);                }
            }
        }

        return $oModel;
    }

    public function delete()
    {
        try {
            Main::getPdoStorage()->beginTransaction();
            $oModel = $this->getRepository()->findBy('id', $this->getRequest('id'));

            if ($this->controlaPersistencia) {
                $this->deleteFunction($oModel);
            } {
                $this->beforeDelete($oModel);
                $this->getRepository()->remove($oModel);
                $this->afterDelete($oModel);
            }

            Main::getPdoStorage()->commit();
            $this->setAvisoRetorno('Registro deletado com sucesso!');
        } catch (\Throwable $t) {
            Main::getPdoStorage()->rollback();
            throw $t;
        }
    }

    protected function beforeChangeStatus($model) {}
    protected function afterChangeStatus($model) {}

    public function status()
    {
        $model = $this->getRepository()->findBy('id', $this->getRequest('id'));
        if ($model instanceof StatusModel) {
            $this->beforeChangeStatus($model);
            $model->toggleSituacao();
            $this->getRepository()->save($model);
            $this->afterChangeStatus($model);
            $this->setAvisoRetorno('Registro atualizado com sucesso!');
            return;
        }

        throw new Mensagem('Para utilizar a estrutura de status é esperado uma instância de StatusModel!');
    }

    protected function setAvisoRetorno($mensagem, $tipo = Aviso::TIPO_SUCESSO) {
        $aviso = new Aviso($mensagem);
        $aviso->setTipo($tipo);
        echo json_encode($aviso->toArray());
    }
}
