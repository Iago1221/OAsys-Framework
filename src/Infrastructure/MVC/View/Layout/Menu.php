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

    /**
     * Renderiza só o conteúdo interno da sidebar referente ao sistema atual (título +
     * lista de módulos). A moldura da sidebar (logo, troca de sistema, rodapé) é
     * responsabilidade de Base.php — este método é reaproveitado tanto no carregamento
     * inicial quanto na troca de sistema via AJAX (substitui o innerHTML de #menu-principal).
     */
    public function render()
    {
        ?>
        <div class="side-menu-titulo">
            <span class="side-menu-titulo-full">Oasys <?= $this->getDescricao() ?></span>
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
            function navegarMenu(rota) {
                // Recolhe a sidebar (se expandida) antes de navegar, para a tela recém
                // aberta não ficar coberta pela sobreposição da sidebar expandida.
                const sideMenu = document.getElementById('side-menu');
                if (sideMenu && !sideMenu.classList.contains('collapsed')) {
                    sideMenu.classList.add('collapsed');
                }
                App.getInstance().openRoute(rota);
            }

            function initializeMenu() {
                const sideMenu = document.getElementById('side-menu');

                document.querySelectorAll('#menu-principal .menu-item > .menu-item-row').forEach((row) => {
                    row.addEventListener('click', (e) => {
                        if (sideMenu && sideMenu.classList.contains('collapsed')) {
                            return; // colapsado: navegação por hover (flyout via CSS)
                        }
                        e.stopPropagation();
                        const li = row.closest('.menu-item');
                        const wasOpen = li.classList.contains('open');
                        li.parentElement.querySelectorAll(':scope > .menu-item.open').forEach((other) => {
                            if (other !== li) other.classList.remove('open');
                        });
                        li.classList.toggle('open', !wasOpen);
                    });
                });

                document.querySelectorAll('#menu-principal .dropdown-item-row').forEach((row) => {
                    row.addEventListener('click', (e) => {
                        e.stopPropagation();
                        row.closest('li').classList.toggle('open');
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
                <li class="menu-item">
                    <div class="menu-item-row" title="<?= htmlspecialchars($oModulo->getTitulo()) ?>">
                        <? if($oModulo->getIcone()): ?>
                            <span class="menu-item-icon"><?= $this->renderIcon($oModulo->getIcone()) ?></span>
                        <? endif; ?>
                        <span class="menu-item-label"><?= $oModulo->getTitulo() ?></span>
                        <span class="menu-item-caret"><?= $this->renderIcon('caret-down', 12) ?></span>
                    </div>
                    <ul class="dropdown" id="dropdown<?= $i ?>">
                        <?php
                        foreach ($oModulo->getItens() as $j => $oItem) {
                            if ($this->auth->podeAcessarItem(Main::getUsuarioId(), $oItem) && !$oItem->getItemPai()) {
                                ?>
                                <li>
                                    <? if ($oItem->getRota()): ?>
                                        <a class="dropdown-link" onclick="navegarMenu('<?= $oItem->getRota()->getNome() ?>')" title="<?= htmlspecialchars($oItem->getTitulo()) ?>">
                                            <? if($oItem->getIcone()): ?>
                                                <span class="menu-item-icon"><?= $this->renderIcon($oItem->getIcone()) ?></span>
                                            <? endif; ?>
                                            <span class="menu-item-label"><?= $oItem->getTitulo() ?></span>
                                        </a>
                                    <? else: ?>
                                        <div class="dropdown-item-row" title="<?= htmlspecialchars($oItem->getTitulo()) ?>">
                                            <? if($oItem->getIcone()): ?>
                                                <span class="menu-item-icon"><?= $this->renderIcon($oItem->getIcone()) ?></span>
                                            <? endif; ?>
                                            <span class="menu-item-label"><?= $oItem->getTitulo() ?></span>
                                            <span class="menu-item-caret"><?= $this->renderIcon('caret-right', 12) ?></span>
                                        </div>
                                        <ul class="dropdown-item" id="dropdown-item<?= $i ?>-<?= $j ?>">
                                            <?php
                                                foreach ($oItem->getItens() as $oSubItem) {
                                                    if ($this->auth->podeAcessarItem(Main::getUsuarioId(), $oSubItem)) {
                                                        ?>
                                                            <li>
                                                                <a onclick="navegarMenu('<?= $oSubItem->getRota()->getNome() ?>')" title="<?= htmlspecialchars($oSubItem->getTitulo()) ?>">
                                                                    <? if($oSubItem->getIcone()): ?>
                                                                        <span class="menu-item-icon"><?= $this->renderIcon($oSubItem->getIcone()) ?></span>
                                                                    <? endif; ?>
                                                                    <span class="menu-item-label"><?= $oSubItem->getTitulo() ?></span>
                                                                </a>
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
