<?php

namespace Framework\Core;

class MemoryRouteRepository implements RouteRepository
{
    private $aRoutes = [];

    public function __construct()
    {
        $this->registerRoute('sys_produto_list',    'Gerenciamento', 'Produto\\ProdutoGridController', 'list',   'Produtos');
        $this->registerRoute('sys_produto_show',    'Gerenciamento', 'Produto\\ProdutoFormController', 'show',   'Visualizar Produto');
        $this->registerRoute('sys_produto_add',     'Gerenciamento', 'Produto\\ProdutoFormController', 'add',    'Adicionar Produto');
        $this->registerRoute('sys_produto_edit',    'Gerenciamento', 'Produto\\ProdutoFormController', 'edit',   'Editar Produto');
        $this->registerRoute('sys_produto_delete',  'Gerenciamento', 'Produto\\ProdutoFormController', 'delete', 'Excluir Produto');

        $this->registerRoute('sys_pessoa_list',         'Gerenciamento', 'Pessoa\\PessoaGridController', 'list',        'Pessoas');
        $this->registerRoute('sys_pessoa_show',         'Gerenciamento', 'Pessoa\\PessoaFormController', 'show',        'Visualizar Pessoas');
        $this->registerRoute('sys_pessoa_add',          'Gerenciamento', 'Pessoa\\PessoaFormController', 'add',         'Adicionar Pessoas');
        $this->registerRoute('sys_pessoa_edit',         'Gerenciamento', 'Pessoa\\PessoaFormController', 'edit',        'Editar Pessoas');
        $this->registerRoute('sys_pessoa_delete',       'Gerenciamento', 'Pessoa\\PessoaFormController', 'delete',      'Excluir Pessoas');
        $this->registerRoute('sys_pessoa_suggest_find', 'Gerenciamento', 'Pessoa\\PessoaGridController', 'suggestFind', 'Buscar Pessoa');
        $this->registerRoute('sys_pessoa_suggest_get',  'Gerenciamento', 'Pessoa\\PessoaGridController', 'suggestGet',  'Encontrar Pessoas');
        $this->registerRoute('sys_pessoa_suggest_list', 'Gerenciamento', 'Pessoa\\PessoaGridController', 'suggestList', 'Listar Pessoas');

        $this->registerRoute('sys_entidade_list',         'Gerenciamento', 'Entidade\\EntidadeGridController', 'list',        'Entidades');
        $this->registerRoute('sys_entidade_show',         'Gerenciamento', 'Entidade\\EntidadeFormController', 'show',        'Visualizar Entidade');
        $this->registerRoute('sys_entidade_add',          'Gerenciamento', 'Entidade\\EntidadeFormController', 'add',         'Adicionar Entidade');
        $this->registerRoute('sys_entidade_edit',         'Gerenciamento', 'Entidade\\EntidadeFormController', 'edit',        'Editar Entidade');
        $this->registerRoute('sys_entidade_delete',       'Gerenciamento', 'Entidade\\EntidadeFormController', 'delete',      'Excluir Entidade');
        $this->registerRoute('sys_entidade_suggest_find', 'Gerenciamento', 'Entidade\\EntidadeGridController', 'suggestFind', 'Buscar Entidade');
        $this->registerRoute('sys_entidade_suggest_get',  'Gerenciamento', 'Entidade\\EntidadeGridController', 'suggestGet',  'Encontrar Entidade');
        $this->registerRoute('sys_entidade_suggest_list', 'Gerenciamento', 'Entidade\\EntidadeGridController', 'suggestList', 'Listar Entidade');

        $this->registerRoute('sys_certificado_list',         'Gerenciamento', 'Certificado\\CertificadoGridController', 'list',        'Certificados Digitais');
        $this->registerRoute('sys_certificado_show',         'Gerenciamento', 'Certificado\\CertificadoFormController', 'show',        'Visualizar Certificado');
        $this->registerRoute('sys_certificado_add',          'Gerenciamento', 'Certificado\\CertificadoFormController', 'add',         'Adicionar Certificado');
        $this->registerRoute('sys_certificado_edit',         'Gerenciamento', 'Certificado\\CertificadoFormController', 'edit',        'Editar Certificado');
        $this->registerRoute('sys_certificado_delete',       'Gerenciamento', 'Certificado\\CertificadoFormController', 'delete',      'Excluir Certificado');
        $this->registerRoute('sys_certificado_suggest_find', 'Gerenciamento', 'Certificado\\CertificadoGridController', 'suggestFind', 'Buscar Certificado');
        $this->registerRoute('sys_certificado_suggest_get',  'Gerenciamento', 'Certificado\\CertificadoGridController', 'suggestGet',  'Encontrar Certificado');
        $this->registerRoute('sys_certificado_suggest_list', 'Gerenciamento', 'Certificado\\CertificadoGridController', 'suggestList', 'Listar Certificado');

        $this->registerRoute('sys_servico_list',         'Gerenciamento', 'Servico\\ServicoGridController', 'list',        'Serviços');
        $this->registerRoute('sys_servico_show',         'Gerenciamento', 'Servico\\ServicoFormController', 'show',        'Visualizar Serviço');
        $this->registerRoute('sys_servico_add',          'Gerenciamento', 'Servico\\ServicoFormController', 'add',         'Adicionar Serviço');
        $this->registerRoute('sys_servico_edit',         'Gerenciamento', 'Servico\\ServicoFormController', 'edit',        'Editar Serviço');
        $this->registerRoute('sys_servico_delete',       'Gerenciamento', 'Servico\\ServicoFormController', 'delete',      'Excluir Serviço');
        $this->registerRoute('sys_servico_suggest_find', 'Gerenciamento', 'Servico\\ServicoGridController', 'suggestFind', 'Buscar Serviço');
        $this->registerRoute('sys_servico_suggest_get',  'Gerenciamento', 'Servico\\ServicoGridController', 'suggestGet',  'Encontrar Serviço');
        $this->registerRoute('sys_servico_suggest_list', 'Gerenciamento', 'Servico\\ServicoGridController', 'suggestList', 'Listar Serviço');


        $this->registerRoute('sys_deposito_list',         'Estoque', 'Deposito\\DepositoGridController', 'list',        'Depositos');
        $this->registerRoute('sys_deposito_show',         'Estoque', 'Deposito\\DepositoFormController', 'show',        'Visualizar Deposito');
        $this->registerRoute('sys_deposito_add',          'Estoque', 'Deposito\\DepositoFormController', 'add',         'Adicionar Deposito');
        $this->registerRoute('sys_deposito_edit',         'Estoque', 'Deposito\\DepositoFormController', 'edit',        'Editar Deposito');
        $this->registerRoute('sys_deposito_delete',       'Estoque', 'Deposito\\DepositoFormController', 'delete',      'Excluir Deposito');

        $this->registerRoute('sys_saldo_list',               'Estoque', 'Saldo\\SaldoGridController',         'list',       'Saldo');
        $this->registerRoute('sys_saldo_movimentar_add',     'Estoque', 'Saldo\\MovimentarFormController',    'movimentar', 'Movimentar Item');
        $this->registerRoute('sys_saldo_movimentacoes_list', 'Estoque', 'Saldo\\MovimentacoesGridController', 'list',       'Movimentar Item');

        $this->registerRoute('sys_usuario_list',          'Sistema', 'Usuario\\UsuarioGridController',      'list',         'Usuários');
        $this->registerRoute('sys_usuario_show',          'Sistema', 'Usuario\\UsuarioFormController',      'show',         'Visualizar Usuário');
        $this->registerRoute('sys_usuario_add',           'Sistema', 'Usuario\\UsuarioFormController',      'add',          'Adicionar Usuário');
        $this->registerRoute('sys_usuario_edit',          'Sistema', 'Usuario\\UsuarioFormController',      'edit',         'Editar Usuário');
        $this->registerRoute('sys_usuario_delete',        'Sistema', 'Usuario\\UsuarioFormController',      'delete',       'Exluir Usuário');
        $this->registerRoute('sys_usuario_edit_password', 'Sistema', 'Usuario\\UsuarioSenhaFormController', 'editPassword', 'Alterar Senha Usuário');

        $this->registerRoute('sys_login',  'Framework', 'LoginController', 'login',  'Login');
        $this->registerRoute('sys_logout', 'Framework', 'LoginController', 'logout', 'Logout');
    }
    public function getRoute($route)
    {
        return $this->aRoutes[$route];
    }

    public function registerRoute($route, $module, $path, $method, $title)
    {
        $this->aRoutes[$route] = ['module' => $module, 'path' => $path, 'method' => $method, 'title' => $title];
    }
}
