<?php

namespace Framework\Infrastructure\MVC\View\Layout;

use Framework\Auth\General;
use Framework\Core\Main;
use Framework\Interface\Domain\Usuario\Usuario;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;

class Base implements ILayout
{
    private Menu $oMenu;

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

    private function getUsuario(): Usuario
    {
        return (new UsuarioRepository(Main::getConnection()))->findBy('id', Main::getUsuarioId());
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
            <title>OASYS - <?= $_SESSION['cliente'] ?></title>
            <link rel="icon" href="/assets/icon.png" sizes="512x512" type="image/png">
            <link rel="stylesheet" href="<?= $sBase ?>/css/styles.css">
            <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.2.1/dist/chartjs-plugin-annotation.min.js"></script>
        </head>
        <?php
    }

    private function renderBody()
    {
        $usuario = $this->getUsuario();


        ?>
        <body>
        <!-- Sidebar: logo + troca de sistema (inicia colapsada, só ícones) -->
        <div id="side-menu" class="side-menu collapsed">
            <div class="side-menu-brand">
                <img src="/assets/icon.png" class="side-menu-logo" alt="Oasys">
            </div>

            <button type="button" class="side-menu-toggle" onclick="App.getInstance().toggleMenu()" title="Expandir/colapsar menu">
                <?= $this->getMenu()->renderIcon('caret-left', 18) ?>
            </button>

            <ul class="side-menu-sistemas">
                <? if ($usuario->getAcessoErp()): ?>
                    <li onclick="App.getInstance().switchSystem('1')" title="ERP">
                        <span class="side-menu-sistema-icon"><?= $this->getMenu()->renderIcon('home', 32) ?></span>
                        <span>ERP</span>
                    </li>
                <? endif; ?>
                <? if ($usuario->getAcessoCrm()): ?>
                    <li onclick="App.getInstance().switchSystem('2')" title="CRM">
                        <span class="side-menu-sistema-icon"><?= $this->getMenu()->renderIcon('users', 32) ?></span>
                        <span>CRM</span>
                    </li>
                <? endif; ?>
                <? if ($usuario->getAcessoGestao()): ?>
                    <li onclick="App.getInstance().switchSystem('3')" title="Gestão Econômica">
                        <span class="side-menu-sistema-icon"><?= $this->getMenu()->renderIcon('wallet', 32) ?></span>
                        <span>Gestão</span>
                    </li>
                <? endif; ?>
                <? if ($usuario->getAcessoVarejo()): ?>
                    <li onclick="App.getInstance().switchSystem('4')" title="Varejo">
                        <span class="side-menu-sistema-icon"><?= $this->getMenu()->renderIcon('shopping-cart-simple', 32) ?></span>
                        <span>Varejo</span>
                    </li>
                <? endif; ?>
                <? if ($usuario->getAcessoIndustria()): ?>
                    <li onclick="App.getInstance().switchSystem('5')" title="Indústria">
                        <span class="side-menu-sistema-icon"><?= $this->getMenu()->renderIcon('gear-six', 32) ?></span>
                        <span>Indústria</span>
                    </li>
                <? endif; ?>
                <? if ($usuario->getAcessoLogistica()): ?>
                    <li onclick="App.getInstance().switchSystem('6')" title="Logística">
                        <span class="side-menu-sistema-icon"><?= $this->getMenu()->renderIcon('truck', 32) ?></span>
                        <span>Logística</span>
                    </li>
                <? endif; ?>
                <? if ($usuario->getAcessoNeuron()): ?>
                    <li onclick="App.getInstance().openRoute('sys_oasys_neuron')" title="Oasys Neuron">
                        <span class="side-menu-sistema-icon"><?= $this->getMenu()->renderIcon('sparkle', 32) ?></span>
                        <span>Neuron</span>
                    </li>
                <? endif; ?>
            </ul>
        </div>

        <div id="main-content" class="main-content">
            <div id="menu-principal">
                <?php
                $this->getMenu()->render();
                ?>
            </div>

            <div id="workspace">
            </div>

            <div id="tabs-bar">
            </div>
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
        <script src="$sBase/js/Components/KanbanComponent.js"></script>
        <script src="$sBase/js/Components/DashboardComponent.js"></script>
        <script src="$sBase/js/Components/GridWithFormComponent.js"></script>
        <script src="$sBase/js/Components/TimelineComponent.js"></script>
        <script src="$sBase/js/Components/CalendarComponent.js"></script>
        <script src="$sBase/js/Components/MapComponent.js"></script>
        <script src="$sBase/js/Components/HtmlRenderComponent.js"></script>
        <script src="$sBase/js/Components/PortalAtualizacaoDetalheComponent.js"></script>
        <script src="$sBase/js/Components/NeuronIntentFormComponent.js"></script>
        <script src="$sBase/js/Components/NeuronIntentFormMountComponent.js"></script>
        <script src="$sBase/js/Components/NeuronComponent.js"></script>
        <script src="$sBase/js/Components/ConciliacaoPanelComponent.js"></script>
        <script src="$sBase/js/app.js"></script>
        <script src="$sBase/js/portal-atualizacoes-boot.js"></script>
        <script>App.getInstance().sUrl = '$sUrl'</script>
        HTML;
    }
}