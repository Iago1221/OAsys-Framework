<?php

namespace Framework\Infrastructure\MVC\Controller;

use Framework\Core\Main;
use Framework\Infrastructure\MVC\View\Interface\MapView;

/**
 * Controller base para tela de mapa (MapComponent).
 * O projeto deve implementar getViewClass() (MapView) e getRepositoryClass(),
 * e em beforeRender() preencher a view com setPoints(), setRoutes(), setRouteOrder(), etc.
 */
abstract class MapController extends Controller
{
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

    /**
     * Exibe o mapa. Em beforeRender() o projeto preenche pontos/rotas na view.
     */
    public function show(bool $bDisabled = false): void
    {
        /** @var MapView $view */
        $view = $this->getView();
        $view->setTitulo($this->title ?? Main::getOrder()->getTitle());
        $view->setRota($this->route ?? Main::getOrder()->getRoute());

        $aData = ['bDisabled' => $bDisabled];
        $this->beforeRender($aData);
        $view->render($aData);
    }

    /**
     * Hook para o projeto preencher a MapView (setPoints, setRoutes, setRouteOrder, setCenter, setZoom, setWidth, etc.).
     *
     * @param array $aData ['bDisabled' => bool, ...]; pode ser alterado para passar dados à view
     */
    protected function beforeRender(array &$aData): void
    {
    }
}
