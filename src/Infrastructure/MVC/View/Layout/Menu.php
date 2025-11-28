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

    public function renderModulos()
    {
        foreach ($this->aModulos as $i => $oModulo) {
            if ($oModulo->isSituacao(Modulo::SITUACAO_ATIVO) && $oModulo->isDisponivel(Main::getUsuarioId())) {
                ?>
                <li class="menu-item <?= $i ?>">
                    <?= $oModulo->getTitulo() ?>
                    <ul class="dropdown" id="dropdown<?= $i ?>">
                        <?php
                        foreach ($oModulo->getItens() as $oItem) {
                            if ($oItem->isSituacao(ModuloItem::SITUACAO_ATIVO)) {
                                ?>
                                <li>
                                    <? if($oItem->getIcone()): ?>
                                        <script>
                                            const svgNS = "http://www.w3.org/2000/svg";
                                            const icon = document.createElementNS(svgNS, "svg");
                                            const menuIcon = await App.getInstance().loadSVG(`/assets/icons/<?= $oItem->getIcone() ?>.svg`)
                                            icon.setAttribute("viewBox", menuIcon.viewBox);
                                            icon.setAttribute("width", "10");
                                            icon.setAttribute("height", "10");

                                            const path = document.createElementNS(svgNS, "path" );
                                            path.setAttribute("d", menuIcon.path);
                                            path.setAttribute("fill", "currentColor");

                                            icon.appendChild(path);
                                            const scriptAtual = document.currentScript
                                            scriptAtual.insertAdjacentElement('afterend', icon)
                                        </script>
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
