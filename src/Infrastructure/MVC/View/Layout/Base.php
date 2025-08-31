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
        $sBase = '/assets/web-components';
        $sUrl = General::$URL;

        ?>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>OASYS</title>
            <link rel="stylesheet" href="<?= $sBase ?>/css/styles.css">
            <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
        </head>
        <?php
    }


    public function renderBody()
    {
        ?><body><?php
        $this->getMenu()->render();
        ?>

        <!-- Menu lateral -->
        <div id="side-menu" class="side-menu">
            <div class="menu-toggle" onclick="App.getInstance().toggleMenu()">
                <i class="fi fi-rr-angle-right"></i>
            </div>
            <ul class="menu-items">
                <li onclick="App.getInstance().switchSystem('1')" title="ERP">
                    <i class="fi fi-tr-house-chimney"></i>
                </li>
                <li onclick="App.getInstance().switchSystem('2')" title="CRM">
                    <i class="fi fi-tr-users"></i>
                </li>
                <li onclick="App.getInstance().switchSystem('3')" title="Gestão Econômica">
                    <i class="fi fi-tr-bank"></i>
                </li>
                <li onclick="App.getInstance().switchSystem('4')" title="Varejo">
                    <i class="fi fi-tr-basket-shopping-simple"></i>
                </li>
                <li onclick="App.getInstance().switchSystem('5')" title="Indústria">
                    <i class="fi fi-tr-industry-alt"></i>
                </li>
            </ul>
        </div>

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

        <!-- Botão flutuante -->
        <div id="chatbot-button">
            💬
        </div>

        <!-- Janela do Chat -->
        <div id="chatbot-window" class="hidden">
            <div class="chatbot-header">
                <span>Oasys Neuron</span>
                <button onclick="App.getInstance().toggleChat()">✖</button>
            </div>
            <div id="chatbot-messages" class="chatbot-messages"></div>
            <div class="chatbot-input-area">
                <input id="chatbot-input" type="text" placeholder="Digite sua mensagem..."
                       onkeydown="if(event.key==='Enter'){App.getInstance().sendMessage();}">
                <button onclick="App.getInstance().sendMessage()">Enviar</button>
            </div>
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
        <script src="$sBase/js/Components/Chatbot.js"></script>
        <script>App.getInstance().sUrl = '$sUrl'</script>
        HTML;
    }
}