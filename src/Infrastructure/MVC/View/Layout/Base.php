<?php

namespace Framework\Infrastructure\MVC\View\Layout;

use Framework\Auth\General;
use Framework\Infrastructure\MVC\View\Layout\ILayout;

class Base implements ILayout
{
    private Menu $oMenu;
    private $acessoErp;
    private $acessoCrm;

    public function __construct($oMenu, $acessoErp, $acessoCrm)
    {
        $this->oMenu = $oMenu;
        $this->acessoErp = $acessoErp;
        $this->acessoCrm = $acessoCrm;

        if ($this->acessoErp && $this->acessoCrm) {
            $this->oMenu->setMudaSistema();
        }
    }

    public function setMenu($oMenu)
    {
        $this->oMenu = $oMenu;
    }

    public function getMenu()
    {
        return $this->oMenu;
    }

    public function render()
    {
        ?>
            <!DOCTYPE html>
            <html lang="pt-BR">
        <?php
        $this->renderHead();
        $this->renderBody();
        ?></html><?php
    }

    private function renderHead()
    {
        ?>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>OASYS</title>
        </head>
        <?php
    }


    private function renderBody()
    {
        ?><body><?php
        $this->getMenu()->render();
        ?>
        <div id="workspace">
        </div>

        <div id="tabs-bar">
        </div>

        <div class="modal">
            <div class="modal-content">
                <fieldset class="modal-fieldset">
                    <legend class="modal-legend">Mensagem</legend>
                    <p class="modal-message"></p>
                </fieldset>
                <button class="principal-form-button" onclick="App.getInstance().closeModal()">Confirmar</button>
            </div>
        </div>

        <div id="loading-overlay" class="loading-overlay">
            <div class="loading-spinner"></div>
        </div>
        <?php
        $this->loadJs();
        ?></body><?php
    }

    private function loadJs()
    {
        $sBase = '/assets/web-components';
        $sUrl = General::$URL;
        echo <<<HTML
        <script src="$sBase/js/Components/FieldsetComponent.js"></script>
        <script src="$sBase/js/Components/FormFieldComponent.js"></script>
        <script src="$sBase/js/Components/GridFieldComponent.js"></script>
        <script src="$sBase/js/Components/SuggestFieldComponent.js"></script>
        <script src="$sBase/js/Components/WindowComponent.js"></script>
        <script src="$sBase/js/Components/FormComponent.js"></script>
        <script src="$sBase/js/Components/GridComponent.js"></script>
        <script src="$sBase/js/Components/TabComponent.js"></script>
        <script src="$sBase/js/Components/GridFormComponent.js"></script>
        <script src="$sBase/js/Components/AvisoComponent.js"></script>
        <script src="$sBase/js/Components/ConfirmComponent.js"></script>
        <script src="$sBase/js/app.js"></script>
        <script>App.getInstance().sUrl = '$sUrl'</script>
        <link rel="stylesheet" href="$sBase/css/styles.css">
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
        HTML;
    }
}