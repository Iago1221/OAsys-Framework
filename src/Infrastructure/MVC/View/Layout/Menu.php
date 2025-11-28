<?php

namespace Framework\Infrastructure\MVC\View\Layout;

use Framework\Core\Main;
use Framework\Interface\Domain\Modulo\Modulo;
use Framework\Interface\Domain\Modulo\ModuloItem;

/**
 * @since 20/03/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class Menu implements ILayout
{
    /** @var Modulo[] */
    private $aModulos;
    private $mudaSistema = false;

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

    public function setMudaSistema($mudaSistema = true)
    {
        $this->mudaSistema = $mudaSistema;
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
            default:
                return 'ERP';
        }
    }

    public function render()
    {
        ?>
        <div class="topbar">
            <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                <h1 style="font-size: 1rem;">
                    Oasys <?= $this->getDescricao() ?>
                </h1>
            </div>

            <h2><a class="logout" onclick="App.getInstance().logout()">Sair</a></h2>

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
            function initializeMenu() {
                const menuItems = document.querySelectorAll('.menu-item');
                menuItems.forEach(item => {
                    item.addEventListener('mouseenter', (e) => {
                        const drop = item.classList[1];
                        document.querySelector(`#dropdown${drop}`).style.display = 'flex';
                    });

                    item.addEventListener('mouseleave', (e) => {
                        const drop = item.classList[1];
                        document.querySelector(`#dropdown${drop}`).style.display = 'none';
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
            if ($oModulo->isSituacao(Modulo::SITUACAO_ATIVO) && $oModulo->isDisponivel(Main::getUsuarioId())) {
                ?>
                <li class="menu-item <?= $i ?>">
                    <? if($oModulo->getIcone()): ?>
                        <?= $this->renderIcon($oModulo->getIcone()) ?>
                    <? endif; ?>
                    <?= $oModulo->getTitulo() ?>
                    <ul class="dropdown" id="dropdown<?= $i ?>">
                        <?php
                        foreach ($oModulo->getItens() as $oItem) {
                            if ($oItem->isSituacao(ModuloItem::SITUACAO_ATIVO)) {
                                ?>
                                <li>
                                    <? if($oItem->getIcone()): ?>
                                        <?= $this->renderIcon($oItem->getIcone()) ?>
                                    <? endif; ?>
                                    <a onclick="App.getInstance().openRoute('<?= $oItem->getRota()->getNome() ?>')"><?= $oItem->getTitulo() ?></a>
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
