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
             * Mostrar/esconder dropdown por mouseenter/mouseleave direto nos elementos é
             * frágil: depende do navegador disparar mouseenter exatamente no submenu ao
             * mover o mouse do gatilho até ele, o que nem sempre acontece de forma
             * confiável (movimento rápido, diagonal, etc — o submenu acaba sumindo antes
             * do usuário conseguir clicar numa rota). Em vez disso, cada par
             * (gatilho, submenu) visível é validado a cada mousemove global: se a posição
             * atual do mouse está dentro da área do gatilho OU do submenu (com uma folga),
             * mantém aberto; senão, agenda esconder com um pequeno atraso (cancelável).
             */
            function initializeMenu() {
                const pares = [];

                document.querySelectorAll('.menu-item').forEach((item) => {
                    const dropdown = item.querySelector(':scope > .dropdown');
                    if (dropdown) pares.push([item, dropdown]);
                });

                document.querySelectorAll('.dropdown > li').forEach((li) => {
                    const sub = li.querySelector(':scope > .dropdown-item');
                    if (sub) pares.push([li, sub]);
                });

                const timers = new WeakMap();

                function dentro(rect, x, y, folga) {
                    return x >= rect.left - folga && x <= rect.right + folga
                        && y >= rect.top - folga && y <= rect.bottom + folga;
                }

                pares.forEach(([gatilho, submenu]) => {
                    gatilho.addEventListener('mouseenter', () => {
                        clearTimeout(timers.get(submenu));
                        submenu.style.display = 'flex';
                    });
                });

                document.addEventListener('mousemove', (e) => {
                    pares.forEach(([gatilho, submenu]) => {
                        if (submenu.style.display === 'none' || submenu.style.display === '') {
                            return;
                        }

                        const sobre = dentro(gatilho.getBoundingClientRect(), e.clientX, e.clientY, 6)
                            || dentro(submenu.getBoundingClientRect(), e.clientX, e.clientY, 6);

                        if (sobre) {
                            clearTimeout(timers.get(submenu));
                            return;
                        }

                        if (!timers.get(submenu)) {
                            timers.set(submenu, setTimeout(() => {
                                submenu.style.display = 'none';
                                timers.delete(submenu);
                            }, 300));
                        }
                    });
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
