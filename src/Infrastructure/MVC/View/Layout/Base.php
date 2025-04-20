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
            <link rel="stylesheet" href="/assets/css/styles.css">
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
        <script src="assets/js/Components/FieldsetComponent.js"></script>
        <script src="assets/js/Components/FormFieldComponent.js"></script>
        <script src="assets/js/Components/GridFieldComponent.js"></script>
        <script src="assets/js/Components/SuggestFieldComponent.js"></script>
        <script src="assets/js/Components/WindowComponent.js"></script>
        <script src="assets/js/Components/FormComponent.js"></script>
        <script src="assets/js/Components/GridComponent.js"></script>
        <script src="assets/js/Components/TabComponent.js"></script>
        <script src="assets/js/Components/GridFormComponent.js"></script>
        <script src="/assets/js/app.js"></script>
        <script>App.getInstance().sUrl = '<?= General::$URL ?>'</script>
        <link rel="stylesheet" href="assets/css/styles.css">
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
        <?php
    }
}