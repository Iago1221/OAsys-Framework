<?php

namespace Framework\Infrastructure\MVC\View\Layout;

use Framework\Auth\General;
use Framework\Infrastructure\MVC\View\Layout\ILayout;

class Base implements ILayout
{
    private $oMenu;

    public function __construct($oMenu)
    {
        $this->oMenu = $oMenu;
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
        ?>
        <script src="../vendor/oasys/framework/web-components/js/Components/FieldsetComponent.js"></script>
        <script src="../vendor/oasys/framework/web-components/js/Components/FormFieldComponent.js"></script>
        <script src="../vendor/oasys/framework/web-components/js/Components/GridFieldComponent.js"></script>
        <script src="../vendor/oasys/framework/web-components/js/Components/SuggestFieldComponent.js"></script>
        <script src="../vendor/oasys/framework/web-components/js/Components/WindowComponent.js"></script>
        <script src="../vendor/oasys/framework/web-components/js/Components/FormComponent.js"></script>
        <script src="../vendor/oasys/framework/web-components/js/Components/GridComponent.js"></script>
        <script src="../vendor/oasys/framework/web-components/js/Components/TabComponent.js"></script>
        <script src="../vendor/oasys/framework/web-components/js/Components/GridFormComponent.js"></script>
        <script src="../vendor/oasys/framework/web-components/js/app.js"></script>
        <script>App.getInstance().sUrl = '<?= General::$URL ?>'</script>
        <link rel="stylesheet" href="../vendor/oasys/framework/web-components/css/styles.css">
        <?php
    }
}