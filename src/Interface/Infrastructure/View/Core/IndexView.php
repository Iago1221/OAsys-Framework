<?php

namespace Framework\Interface\Infrastructure\View\Core;

use Framework\Infrastructure\MVC\View\Interface\View;
use Framework\Infrastructure\MVC\View\Layout\Base;
use Framework\Infrastructure\MVC\View\Layout\Menu;
use Framework\Interface\Domain\Modulo\Modulo;
use Framework\Interface\Domain\Modulo\ModuloItem;

class IndexView extends View
{
    private $oMenu;

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->setLayout(new Base($this->getMenu()));
    }

    private function getMenu()
    {
        return $this->oMenu;
    }

    protected function create()
    {
        $this->oMenu = new Menu();
        $this->oMenu->setModulos($this->aData['modulos']);
    }

    protected function createModuloGerenciamento()
    {
        $oItemProdutos = new ModuloItem('sys_produto_list', 'Produtos');
        $oItemPessoas = new ModuloItem('sys_pessoa_list', 'Pessoas');
        $oItemEntidades = new ModuloItem('sys_entidade_list', 'Entidades');
        $oItemCertificadosDigitais = new ModuloItem('sys_certificado_list', 'Certificados Digitais');
        $oItemServicos = new ModuloItem('sys_servico_list', 'Serviços');
        $oModulo = new Modulo('Gerenciamento');
        $oModulo->addItem($oItemProdutos);
        $oModulo->addItem($oItemPessoas);
        $oModulo->addItem($oItemCertificadosDigitais);
        $oModulo->addItem($oItemEntidades);
        $oModulo->addItem($oItemServicos);
        $this->oMenu->addModulo($oModulo);
    }

    protected function createModuloPedido()
    {
        $oItemVenda = new ModuloItem('sys_pedido_venda_list', 'Vendas');
        $oItemCompra = new ModuloItem('sys_pedido_compra_list', 'Compras');
        $oItemOrcamentos = new ModuloItem('sys_orcamento_list', 'Orçamentos');
        $oModulo = new Modulo('Pedido');
        $oModulo->addItem($oItemVenda);
        $oModulo->addItem($oItemCompra);
        $oModulo->addItem($oItemOrcamentos);
        $this->oMenu->addModulo($oModulo);
    }

    protected function createModuloFinanceiro()
    {
        $oItemContasPagar = new ModuloItem('sys_contas_pagar_list', 'Contas a Pagar');
        $oItemContasReceber = new ModuloItem('sys_contas_receber_list', 'Contas a Receber');
        $oItemRelatorios = new ModuloItem('sys_relatorios_financeiros_list', 'Relatorios');
        $oModulo = new Modulo('Financeiro');
        $oModulo->addItem($oItemContasPagar);
        $oModulo->addItem($oItemContasReceber);
        $oModulo->addItem($oItemRelatorios);
        $this->oMenu->addModulo($oModulo);
    }

    protected function createModuloFaturamento()
    {
        $oItemNotasFiscaisSaida = new ModuloItem('sys_notas_fiscais_saida', 'Notas Fiscais Saída');
        $oItemNotasFiscaisEntrada = new ModuloItem('sys_notas_fiscais_entrada', 'Notas Fiscais Entrada');
        $oItemCfop = new ModuloItem('sys_cfop_list', 'CFOP');
        $oModulo = new Modulo('Faturamento');
        $oModulo->addItem($oItemNotasFiscaisSaida);
        $oModulo->addItem($oItemNotasFiscaisEntrada);
        $oModulo->addItem($oItemCfop);
        $this->oMenu->addModulo($oModulo);
    }

    protected function createModuloEstoque()
    {
        $oItemDepositos = new ModuloItem('sys_deposito_list', 'Depositos');
        $oItemEstoque = new ModuloItem('sys_saldo_list', 'Saldo');
        $oModulo = new Modulo('Estoque');
        $oModulo->addItem($oItemDepositos);
        $oModulo->addItem($oItemEstoque);
        $this->oMenu->addModulo($oModulo);
    }

    protected function createModuloSistema()
    {
        $oItemUsuarios = new ModuloItem('sys_usuario_list', 'Usuarios');
        $oModulo = new Modulo('Sistema');
        $oModulo->addItem($oItemUsuarios);
        $this->oMenu->addModulo($oModulo);
    }

    public function render()
    {
        throw new \DomainException('Não se pode renderizar componentes na raiz!');
    }
}
