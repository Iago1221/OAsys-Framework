<?php

namespace Framework\Infrastructure\MVC\View\Layout;

use Framework\Core\Main;
use Framework\Interface\Application\Auth\AuthorizationService;
use Framework\Interface\Domain\Modulo\Modulo;

/**
 * @since 20/03/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class Menu implements ILayout
{
    /** @var Modulo[] */
    private $aModulos;
    protected AuthorizationService $auth;

    public function __construct(AuthorizationService $auth)
    {
        $this->auth = $auth;
    }

    public function setModulos($aModulos)
    {
        foreach ($aModulos as $oModulo) {
            $this->addModulo($oModulo);
        }
    }

    public function addModulo($oModulo)
    {
        $this->aModulos[] = $oModulo;
    }

    private function getDescricao()
    {
        $sistema = $_SESSION['sistema'] ?? 1;

        switch ($sistema) {
            case 1:
                return 'ERP';
            case 2:
                return 'CRM';
            case 3:
                return 'Gestão';
            case 4:
                return 'Varejo';
            case 5:
                return 'Indústria';
            case 6:
                return 'Logística';
            default:
                return 'ERP';
        }
    }

    public function render()
    {
        ?>
        <div class="topbar">
            <div class="topbar-title">
                <h1>Oasys <?= $this->getDescricao() ?></h1>
            </div>

            <div class="topbar-actions">
                <span id="oasys-header-novidades-slot" class="header-novidades-slot">
                    <a id="oasys-novidades-link" class="header-novidades-link topbar-icon-btn" onclick="App.getInstance().openRoute('sys_atualizacao_portal_list')" title="Novidades">
                        <?= $this->renderIcon('megaphone', 16) ?>
                    </a>
                </span>

                <button type="button" class="logout topbar-icon-btn" onclick="App.getInstance().logout()" title="Sair">
                    <?= $this->renderIcon('sign-out', 16) ?>
                </button>
            </div>

        </div>
        <nav class="menu">
            <ul class="menu-list">
        <?php
        $this->renderModulos();
        ?>
            </ul>
        </nav>
        <?php
        $this->addScript();
    }

    public function addScript()
    {
        ?>
        <script>
            /**
             * Liga um par (gatilho, submenu) com um pequeno atraso antes de esconder
             * (em vez de esconder no mouseleave imediatamente). Sem esse atraso, qualquer
             * imprecisão no caminho do mouse ao subir do item até o submenu (movimento
             * rápido, diagonal, ou até simulação de input) passa por uma fração de segundo
             * fora de ambos os elementos e o navegador dispara mouseleave, escondendo o
             * submenu antes do usuário conseguir clicar em uma rota.
             */
            function ligarSubmenuComAtraso(gatilho, submenu) {
                if (!submenu) return;
                let timer = null;

                const mostrar = () => {
                    clearTimeout(timer);
                    submenu.style.display = 'flex';
                };
                const esconderComAtraso = () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => { submenu.style.display = 'none'; }, 300);
                };

                gatilho.addEventListener('mouseenter', mostrar);
                gatilho.addEventListener('mouseleave', esconderComAtraso);
                submenu.addEventListener('mouseenter', mostrar);
                submenu.addEventListener('mouseleave', esconderComAtraso);
            }

            function initializeMenu() {
                document.querySelectorAll('.menu-item').forEach((item) => {
                    ligarSubmenuComAtraso(item, item.querySelector(':scope > .dropdown'));
                });

                document.querySelectorAll('.dropdown > li').forEach((li) => {
                    ligarSubmenuComAtraso(li, li.querySelector(':scope > .dropdown-item'));
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                initializeMenu();
            });
        </script>
        <?php
    }

    function renderIcon(string $nome, int $size = 16): string
    {
        $arquivo = $_SERVER['DOCUMENT_ROOT'] . "/assets/icons/{$nome}.svg";

        if (!file_exists($arquivo)) {
            return '';
        }

        $svg = file_get_contents($arquivo);

        $svg = preg_replace('/\s(width|height)="[^"]*"/i', '', $svg);
        $svg = preg_replace('/style="[^"]*"/i', '', $svg);

        $svg = preg_replace(
                '/<svg/i',
                '<svg width="'.$size.'" height="'.$size.'" fill="currentColor" style="vertical-align:middle;"',
                $svg,
                1
        );

        return $svg;
    }

    public function renderModulos()
    {
        foreach ($this->aModulos as $i => $oModulo) {
            if ($oModulo->isDisponivel(Main::getUsuarioId()) && $this->auth->podeAcessarModulo(Main::getUsuarioId(), $oModulo)) {
                ?>
                <li class="menu-item <?= $i ?>">
                    <? if($oModulo->getIcone()): ?>
                        <?= $this->renderIcon($oModulo->getIcone(), 18) ?>
                    <? endif; ?>
                    <?= $oModulo->getTitulo() ?>
                    <ul class="dropdown" id="dropdown<?= $i ?>">
                        <?php
                        foreach ($oModulo->getItens() as $oItem) {
                            if ($this->auth->podeAcessarItem(Main::getUsuarioId(), $oItem) && !$oItem->getItemPai()) {
                                ?>
                                <li>
                                    <? if($oItem->getIcone()): ?>
                                        <?= $this->renderIcon($oItem->getIcone(), 16) ?>
                                    <? endif; ?>
                                    <? if ($oItem->getRota()): ?>
                                        <a onclick="App.getInstance().openRoute('<?= $oItem->getRota()->getNome() ?>')"><?= $oItem->getTitulo() ?></a>
                                    <? else: ?>
                                        <a> <?=$oItem->getTitulo() ?> </a>
                                        <?= $this->renderIcon('caret-right', 12) ?>
                                        <ul class="dropdown-item" id="dropdown-item<?= $i ?>">
                                            <?php
                                                foreach ($oItem->getItens() as $oSubItem) {
                                                    if ($this->auth->podeAcessarItem(Main::getUsuarioId(), $oSubItem)) {
                                                        ?>
                                                            <li>
                                                                <? if($oSubItem->getIcone()): ?>
                                                                    <?= $this->renderIcon($oSubItem->getIcone(), 16) ?>
                                                                <? endif; ?>
                                                                <a onclick="App.getInstance().openRoute('<?= $oSubItem->getRota()->getNome() ?>')"><?= $oSubItem->getTitulo() ?></a>
                                                            </li>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </ul>
                                    <? endif; ?>
                                 </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </li>
                <?php
            }
        }
    }
}
