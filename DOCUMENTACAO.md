# Documentação Técnica - OAsys Framework

## Índice

1. [Visão Geral](#visão-geral)
2. [Arquitetura](#arquitetura)
3. [Requisitos e Instalação](#requisitos-e-instalação)
4. [Configuração](#configuração)
5. [Estrutura do Projeto](#estrutura-do-projeto)
6. [Documentação da API](#documentação-da-api)
7. [Guia do Desenvolvedor](#guia-do-desenvolvedor)
8. [Autenticação e Segurança](#autenticação-e-segurança)
9. [Persistência de Dados](#persistência-de-dados)
10. [Exemplos de Uso](#exemplos-de-uso)

---

## Visão Geral

O **OAsys Framework** é um framework PHP moderno baseado em arquitetura MVC (Model-View-Controller) que oferece uma estrutura robusta para desenvolvimento de aplicações web. O framework foi projetado para simplificar o desenvolvimento, fornecendo componentes reutilizáveis, sistema de rotas dinâmico, autenticação JWT, e uma camada de abstração de dados poderosa.

### Características Principais

- **Arquitetura MVC**: Separação clara de responsabilidades
- **Sistema de Rotas Dinâmico**: Rotas baseadas em banco de dados
- **Autenticação JWT**: Sistema seguro de autenticação baseado em tokens
- **ORM Abstrato**: Camada de persistência com repositórios e relacionamentos
- **Componentes de Interface**: Sistema de componentes para Grid, Form, Kanban e Dashboard
- **Migrações de Banco**: Sistema de migração de esquema de banco de dados
- **Logs Automáticos**: Sistema de log automático para operações CRUD
- **Multi-tenant**: Suporte para arquitetura multi-tenant

### Tecnologias Utilizadas

- **PHP 8.0+**
- **PDO** para acesso a banco de dados
- **Firebase JWT** para autenticação
- **Composer** para gerenciamento de dependências
- **Web Components** para interface frontend

---

## Arquitetura

### Visão Geral da Arquitetura

O OAsys Framework segue uma arquitetura em camadas bem definida:

```
┌─────────────────────────────────────┐
│         Camada de Apresentação      │
│         (Views e Components)        │
└─────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────┐
│         Camada de Controle          │
│         (Controllers)               │
└─────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────┐
│         Camada de Domínio           │
│         (Models e Interfaces)       │
└─────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────┐
│      Camada de Persistência         │
│      (Repositories e Storage)       │
└─────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────┐
│         Banco de Dados              │
└─────────────────────────────────────┘
```

### Componentes Principais

#### 1. Core (Núcleo)

- **Main**: Classe principal que gerencia requisições e conexões com banco de dados
- **Router**: Sistema de roteamento baseado em pedidos (Order)
  - `OrderFactory`: Fabrica pedidos a partir de rotas
  - `OrderProcessing`: Processa os pedidos e chama os controllers

#### 2. Infrastructure (Infraestrutura)

- **MVC**: Implementação do padrão MVC
  - **Controllers**: `Controller`, `FormController`, `GridController`, `KanbanController`
  - **Models**: Classe base `Model` com serialização JSON
  - **Views**: Sistema de views com componentes reutilizáveis
- **DB/Persistence**: Camada de persistência
  - **Repository**: Repositório abstrato com query builder
  - **Storage**: Abstração de armazenamento (PdoStorage)
  - **Migrations**: Sistema de migração de banco de dados
- **CLI**: Ferramentas de linha de comando
  - **Scheduler**: Sistema de agendamento de tarefas

#### 3. Auth (Autenticação)

- **Autenticator**: Gerencia autenticação JWT
- **General**: Configurações gerais de autenticação

#### 4. Interface (Camada de Interface)

- **Domain**: Modelos de domínio (Usuario, Rota, Log, etc.)
- **Infrastructure**: Implementações específicas
  - **Controllers**: Controllers de sistema
  - **Persistence**: Repositórios específicos
  - **View**: Views específicas

### Fluxo de Requisição

1. **Requisição HTTP** → Cliente faz uma requisição
2. **Main::__construct** → Recebe a rota e busca no banco de dados
3. **OrderFactory** → Cria um pedido (Order) a partir da rota
4. **Autenticator::verifyToken** → Verifica autenticação JWT
5. **OrderProcessing** → Processa o pedido
6. **Factory::loadController** → Carrega o controller apropriado
7. **Controller::method** → Executa o método do controller
8. **Repository** → Acessa dados se necessário
9. **View::render** → Renderiza a resposta
10. **Resposta HTTP** → Retorna ao cliente

---

## Requisitos e Instalação

### Requisitos do Sistema

- **PHP**: 8.0 ou superior
- **Extensões PHP**:
  - `ext-pdo`: Para acesso a banco de dados
  - `ext-fileinfo`: Para manipulação de arquivos
  - `ext-gd`: Para manipulação de imagens
- **Composer**: Para gerenciamento de dependências
- **Banco de Dados**: PostgreSQL (recomendado) ou MySQL
- **Servidor Web**: Apache ou Nginx com PHP-FPM

### Instalação

1. **Clone o repositório ou baixe o projeto**

```bash
git clone <repository-url>
cd OAsys-Framework
```

2. **Instale as dependências via Composer**

```bash
composer install
```

3. **Configure o autoload**

O autoload do Composer já está configurado no `composer.json`:

```json
{
    "autoload": {
        "psr-4": {
            "Framework\\": "src/"
        }
    }
}
```

4. **Configure o servidor web**

Configure o servidor web para apontar para o diretório público do seu projeto. O framework não possui um diretório público específico, então você precisará configurar isso de acordo com sua estrutura de projeto.

---

## Configuração

### Configuração do Banco de Dados

Configure a conexão com o banco de dados usando o método `Main::setBdConfig()`:

```php
use Framework\Core\Main;

Main::setBdConfig([
    'dsn' => 'pgsql:host=localhost;dbname=oasys_db',
    'user' => 'usuario',
    'password' => 'senha'
]);
```

### Configuração do Ambiente

Configure o ambiente de execução usando `Main::setConfig()`:

```php
use Framework\Core\Main;

Main::setConfig([
    'ambiente' => 'DEV' // ou 'QA' ou 'PROD'
]);
```

### Configuração de Autenticação

Configure a chave secreta JWT em `src/Auth/General.php`:

```php
namespace Framework\Auth;

class General
{
    static $SECRET_JWT = 'sua_chave_secreta_aqui';
    static $URL = 'http://localhost:8080';
}
```

**⚠️ IMPORTANTE**: Altere a chave secreta em produção!

### Configuração de Rotas

As rotas são armazenadas no banco de dados na tabela `rotas`. Estrutura esperada:

- `id`: ID da rota
- `nome`: Nome único da rota (ex: `sys_usuario_list`)
- `caminho`: Caminho do controller (ex: `UsuarioGridController`)
- `metodo`: Método a ser chamado (ex: `list`)
- `pacote`: Pacote do controller (ex: `Sistema`)
- `titulo`: Título exibido na interface

Exemplo de inserção de rota:

```sql
INSERT INTO rotas (nome, caminho, metodo, pacote, titulo) 
VALUES ('sys_usuario_list', 'UsuarioGridController', 'list', 'Sistema', 'Listagem de Usuários');
```

---

## Estrutura do Projeto

```
OAsys-Framework/
├── src/
│   ├── Auth/                      # Autenticação
│   │   ├── Autenticator.php      # Gerencia autenticação JWT
│   │   └── General.php           # Configurações de autenticação
│   │
│   ├── Core/                      # Núcleo do framework
│   │   ├── Main.php              # Classe principal
│   │   └── Router/               # Sistema de roteamento
│   │       ├── OrderFactory.php  # Fabrica pedidos
│   │       └── OrderProcessing.php # Processa pedidos
│   │
│   ├── Infrastructure/            # Infraestrutura
│   │   ├── Factory.php           # Factory para controllers e models
│   │   ├── Mensagem.php          # Tratamento de mensagens
│   │   ├── Response.php          # Respostas HTTP padronizadas
│   │   │
│   │   ├── CLI/                  # Ferramentas CLI
│   │   │   ├── bin/
│   │   │   │   └── MigrateCLI.php
│   │   │   └── Scheduler/
│   │   │
│   │   ├── DB/                   # Banco de dados
│   │   │   ├── Migrations/       # Migrações
│   │   │   └── Persistence/      # Persistência
│   │   │       ├── Repository/   # Repositórios
│   │   │       └── Storage/      # Storage
│   │   │
│   │   └── MVC/                  # MVC
│   │       ├── Controller/       # Controllers
│   │       ├── Model/            # Models
│   │       └── View/             # Views e Components
│   │
│   └── Interface/                 # Camada de interface
│       ├── Domain/               # Modelos de domínio
│       └── Infrastructure/       # Implementações
│
├── vendor/                        # Dependências do Composer
├── composer.json                  # Configuração do Composer
└── DOCUMENTACAO.md               # Esta documentação
```

---

## Documentação da API

### Classe Main

Classe principal do framework que gerencia requisições e conexões.

#### Métodos Estáticos

##### `setBdConfig(array $aBDConfig): void`

Define a configuração do banco de dados. Deve ser chamado uma única vez no início da aplicação.

**Parâmetros:**
- `$aBDConfig`: Array com as configurações:
  - `dsn`: String de conexão PDO
  - `user`: Usuário do banco
  - `password`: Senha do banco

**Exemplo:**
```php
Main::setBdConfig([
    'dsn' => 'pgsql:host=localhost;dbname=oasys',
    'user' => 'postgres',
    'password' => 'senha123'
]);
```

##### `setConfig(array $config): void`

Define a configuração do ambiente.

**Parâmetros:**
- `$config`: Array com configurações:
  - `ambiente`: 'DEV', 'QA' ou 'PROD'

##### `getConnection(): PDO`

Retorna a instância PDO conectada ao banco de dados.

##### `getPdoStorage(): PdoStorage`

Retorna a instância do PdoStorage para operações de banco.

##### `getUsuarioId(): ?int`

Retorna o ID do usuário autenticado.

##### `getOrder(): Order`

Retorna o pedido (Order) atual sendo processado.

##### `isRoute(string $route): bool`

Verifica se a rota atual corresponde à rota passada.

##### `isAmbienteDesenvolvimento(): bool`

Verifica se está em ambiente de desenvolvimento.

##### `isAmbienteQualidade(): bool`

Verifica se está em ambiente de qualidade.

##### `isAmbienteProducao(): bool`

Verifica se está em ambiente de produção.

---

### Classe Repository

Classe abstrata base para repositórios. Fornece métodos para manipulação de dados.

#### Métodos Públicos

##### `findBy(string $column, $value): ?object`

Busca um registro por coluna e valor.

**Exemplo:**
```php
$usuario = $repository->findBy('email', 'usuario@example.com');
```

##### `findAllBy(string $column, $value): array`

Busca todos os registros que correspondem ao critério.

##### `save(object $model): bool`

Salva um modelo (insert ou update).

**Exemplo:**
```php
$usuario = new Usuario();
$usuario->setNome('João');
$usuario->setEmail('joao@example.com');
$repository->save($usuario);
```

##### `saveWithRelations(object $model): bool`

Salva um modelo incluindo seus relacionamentos hasMany.

##### `remove(object $model): bool`

Remove um registro do banco de dados.

##### `filterBy(array $conditions): self`

Adiciona filtros à consulta.

**Exemplo:**
```php
$repository->filterBy([
    'nome' => 'João',
    ['name' => 'email', 'operator' => 'CONTEM', 'value' => '@gmail.com']
]);
```

**Operadores disponíveis:**
- `IGUAL` ou `=`
- `DIFERENTE` ou `<>`
- `CONTEM` ou `ILIKE`
- `MAIOR` ou `>`
- `MENOR` ou `<`
- `MAIOR IGUAL` ou `>=`
- `MENOR IGUAL` ou `<=`

##### `orderBy(string $column, string $direction = 'asc'): self`

Define a ordenação da consulta.

**Exemplo:**
```php
$repository->orderBy('nome', 'ASC');
```

##### `limit(int $limit): self`

Define o limite de registros.

##### `offset(int $offset): self`

Define o offset para paginação.

##### `paginate(int $perPage, int $page = 1): array`

Realiza paginação dos resultados.

**Exemplo:**
```php
$usuarios = $repository->paginate(10, 1); // 10 registros por página, página 1
```

##### `get(): array`

Executa a consulta e retorna os resultados.

##### `count(): int`

Conta os registros que correspondem aos filtros.

##### `with(array $relations): self`

Carrega relacionamentos eager loading.

**Exemplo:**
```php
$repository->with(['perfil', 'empresa']);
```

##### `addJoin(...): self`

Adiciona um JOIN à consulta.

**Parâmetros:**
- `$schema`: Schema da tabela
- `$table`: Nome da tabela
- `$localColumn`: Coluna local
- `$foreignColumn`: Coluna estrangeira
- `$type`: Tipo do JOIN ('INNER', 'LEFT', 'RIGHT')
- `$alias`: Alias da tabela
- `$lateral`: Se é JOIN LATERAL

#### Relacionamentos

##### `hasOne($model, string $relatedClass, string $foreignKey, Repository $relatedRepository, string $localKey = 'id'): void`

Define relacionamento 1 para 1.

##### `hasMany($model, string $relatedClass, string $foreignKey, Repository $relatedRepository, string $localKey = 'id'): void`

Define relacionamento 1 para muitos.

##### `belongsTo($model, string $relatedClass, string $ownerKey, string $foreignKey, Repository $relatedRepository): void`

Define relacionamento muitos para 1.

##### `belongsToMany($model, string $relatedClass, Repository $relatedRepository, string $pivotTable, string $foreignPivotKey, string $relatedPivotKey, string $localKey = 'id'): void`

Define relacionamento muitos para muitos.

##### `attach($model, string $pivotTable, string $foreignKey, string $relatedKey, array $relatedIds): void`

Anexa registros em tabela pivot.

##### `detach($model, string $pivotTable, string $foreignKey, string $relatedKey, ?array $relatedIds = null): void`

Remove relacionamentos em tabela pivot.

##### `sync($model, string $pivotTable, string $foreignKey, string $relatedKey, array $relatedIds): void`

Sincroniza relacionamentos em tabela pivot.

---

### Classe Controller

Classe abstrata base para controllers.

#### Métodos Protegidos

##### `getRepository(): Repository`

Retorna a instância do repositório.

##### `getView(): View`

Retorna a instância da view.

##### `getRequest($data = null): mixed`

Retorna os dados da requisição. Se `$data` for especificado, retorna apenas esse campo.

**Exemplo:**
```php
$todosDados = $this->getRequest();
$email = $this->getRequest('email');
```

##### `setParam(string $sParamName, $xParamValue): void`

Define um parâmetro na requisição.

##### `mapModelToArray($model): array`

Converte um modelo para array.

---

### Classe FormController

Controller base para formulários (CRUD).

#### Métodos Públicos

##### `show(bool $bDisabled = true, bool $instanciaModel = true): void`

Exibe o formulário.

**Parâmetros:**
- `$bDisabled`: Se o formulário deve estar desabilitado
- `$instanciaModel`: Se deve instanciar o modelo a partir do ID na URL

##### `add(): void`

Adiciona um novo registro. Suporta GET (exibe formulário) e POST (salva dados).

##### `edit(): void`

Edita um registro existente. Suporta GET (exibe formulário) e POST (atualiza dados).

##### `delete(): void`

Remove um registro.

##### `status(): void`

Altera o status de um registro (para modelos que implementam `StatusModel`).

#### Métodos Protegidos (Hooks)

##### `beforeAdd($model): void`

Chamado antes de adicionar um registro.

##### `afterAdd($model): void`

Chamado depois de adicionar um registro.

##### `beforeEdit($model): void`

Chamado antes de editar um registro.

##### `afterEdit($model): void`

Chamado depois de editar um registro.

##### `beforeDelete($model): void`

Chamado antes de deletar um registro.

##### `afterDelete($model): void`

Chamado depois de deletar um registro.

##### `beforeRender($oModel, &$aData): void`

Chamado antes de renderizar a view.

##### `formBean($oModel): void`

Popula os campos do formulário com os dados do modelo.

---

### Classe GridController

Controller base para grids (listagens).

#### Métodos Públicos

##### `list(): void`

Lista os registros com paginação e filtros.

##### `export(array $aData): void`

Exporta os dados para CSV.

##### `suggestList(): void`

Lista registros para sugestão (sem ações).

##### `suggestFind(): void`

Busca um registro por ID para sugestão.

##### `suggestGet(): void`

Busca registros para sugestão com filtro.

#### Métodos Protegidos (Hooks)

##### `trataFiltros(&$filtros): void`

Trata os filtros antes de aplicar à consulta.

##### `beforeSetRegistros(): void`

Chamado antes de buscar os registros.

##### `beforeBeanRegistro(&$registro): void`

Chamado antes de converter cada registro para array.

##### `beforeBindView(): void`

Chamado antes de vincular dados à view.

##### `beforeRender(): void`

Chamado antes de renderizar a view.

---

### Classe Autenticator

Gerencia autenticação JWT.

#### Métodos Públicos

##### `login(): bool`

Valida as credenciais de login.

##### `generateToken(): string|false`

Gera um token JWT para o usuário autenticado.

##### `verifyToken(): bool`

Verifica se o token na sessão é válido.

---

## Guia do Desenvolvedor

### Criando um Model

1. Crie uma classe que estende `Framework\Infrastructure\MVC\Model\Model`:

```php
<?php

namespace Framework\Interface\Domain\Produto;

use Framework\Infrastructure\MVC\Model\Model;

class Produto extends Model
{
    protected ?int $id;
    protected ?string $nome;
    protected ?float $preco;
    protected ?string $descricao;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    // ... outros getters e setters
}
```

### Criando um Repository

1. Crie uma classe que estende `Framework\Infrastructure\DB\Persistence\Repository\Repository`:

```php
<?php

namespace Framework\Interface\Infrastructure\Persistence\Produto;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Produto\Produto;

class ProdutoRepository extends Repository
{
    protected function getModelClass(): string
    {
        return Produto::class;
    }

    protected function getSchema(): ?string
    {
        return 'oasys'; // ou null se não usar schema
    }

    protected function getTableName(): string
    {
        return 'produtos';
    }
}
```

### Criando um FormController

1. Crie uma classe que estende `Framework\Infrastructure\MVC\Controller\FormController`:

```php
<?php

namespace Framework\Interface\Infrastructure\Controllers\Produto;

use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Interface\Infrastructure\Persistence\Produto\ProdutoRepository;
use Framework\Interface\Infrastructure\View\Produto\ProdutoFormView;

class ProdutoFormController extends FormController
{
    protected function getViewClass(): string
    {
        return ProdutoFormView::class;
    }

    protected function getRepositoryClass(): string
    {
        return ProdutoRepository::class;
    }

    protected function beforeAdd($model)
    {
        parent::beforeAdd($model);
        // Validações ou processamentos antes de adicionar
    }

    protected function afterAdd($model)
    {
        parent::afterAdd($model);
        // Processamentos após adicionar
    }
}
```

### Criando um GridController

1. Crie uma classe que estende `Framework\Infrastructure\MVC\Controller\GridController`:

```php
<?php

namespace Framework\Interface\Infrastructure\Controllers\Produto;

use Framework\Infrastructure\MVC\Controller\GridController;
use Framework\Interface\Infrastructure\Persistence\Produto\ProdutoRepository;
use Framework\Interface\Infrastructure\View\Produto\ProdutoGridView;

class ProdutoGridController extends GridController
{
    protected function getViewClass(): string
    {
        return ProdutoGridView::class;
    }

    protected function getRepositoryClass(): string
    {
        return ProdutoRepository::class;
    }

    protected function trataFiltros(&$filtros)
    {
        // Tratar filtros específicos se necessário
    }
}
```

### Criando uma View

1. Crie uma classe que estende `Framework\Infrastructure\MVC\View\Interface\FormView` ou `GridView`:

```php
<?php

namespace Framework\Interface\Infrastructure\View\Produto;

use Framework\Infrastructure\MVC\View\Interface\FormView;
use Framework\Infrastructure\MVC\View\Components\Form\Form;
use Framework\Infrastructure\MVC\View\Components\Fields\FormField;

class ProdutoFormView extends FormView
{
    protected function instanciaViewComponent()
    {
        $form = new Form();
        
        $fieldNome = new FormField('nome', 'Nome');
        $fieldPreco = new FormField('preco', 'Preço');
        
        $form->addField($fieldNome);
        $form->addField($fieldPreco);
        
        $this->setViewComponent($form);
    }

    protected function create()
    {
        // Configurações adicionais da view
    }

    public function render()
    {
        // Lógica de renderização
    }
}
```

### Trabalhando com Relacionamentos

#### Relacionamento 1 para 1

No repositório:

```php
protected function loadPerfil($usuario)
{
    $perfilRepository = new PerfilRepository($this->pdo);
    $this->hasOne($usuario, 'perfil', 'usuario_id', $perfilRepository);
}
```

#### Relacionamento 1 para Muitos

No repositório:

```php
protected function loadPedidos($cliente)
{
    $pedidoRepository = new PedidoRepository($this->pdo);
    $this->hasMany($cliente, 'pedidos', 'cliente_id', $pedidoRepository);
}
```

#### Relacionamento Muitos para Muitos

No repositório:

```php
protected function loadCategorias($produto)
{
    $categoriaRepository = new CategoriaRepository($this->pdo);
    $this->belongsToMany(
        $produto, 
        'categorias', 
        $categoriaRepository, 
        'produto_categoria',
        'produto_id',
        'categoria_id'
    );
}
```

Uso:

```php
$produtos = $repository->with(['categorias'])->get();
```

### Trabalhando com Transações

O framework gerencia transações automaticamente nos métodos `add`, `edit` e `delete` do `FormController`. Para controle manual:

```php
use Framework\Core\Main;

Main::getPdoStorage()->beginTransaction();
try {
    // Operações de banco
    Main::getPdoStorage()->commit();
} catch (\Exception $e) {
    Main::getPdoStorage()->rollback();
    throw $e;
}
```

### Criando Migrações

1. Crie uma classe que implementa `Framework\Infrastructure\DB\Migrations\IMigration`:

```php
<?php

namespace Framework\Infrastructure\DB\Migrations;

use Framework\Infrastructure\DB\Migrations\IMigration;
use Framework\Core\Main;

class CriarTabelaProdutos implements IMigration
{
    public function up()
    {
        $sql = "
            CREATE TABLE produtos (
                id SERIAL PRIMARY KEY,
                nome VARCHAR(255) NOT NULL,
                preco DECIMAL(10,2),
                descricao TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ";
        
        Main::getPdoStorage()->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS produtos;";
        Main::getPdoStorage()->exec($sql);
    }
}
```

Execute as migrações via CLI:

```bash
php src/Infrastructure/CLI/bin/MigrateCLI.php
```

---

## Autenticação e Segurança

### Sistema de Autenticação JWT

O framework utiliza JWT (JSON Web Tokens) para autenticação.

#### Processo de Login

1. **Validação de Credenciais**:

```php
use Framework\Auth\Autenticator;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioRepository;
use Framework\Core\Main;

$autenticator = new Autenticator(
    $email,
    $senha,
    new UsuarioRepository(Main::getConnection())
);

if ($autenticator->login()) {
    $token = $autenticator->generateToken();
    $_SESSION['oasys-token'] = $token;
    $_SESSION['usuario'] = $usuarioId;
}
```

2. **Verificação de Token**:

O framework verifica automaticamente o token em cada requisição através do `Autenticator::verifyToken()`. Se o token for inválido, o usuário é redirecionado para a página de login.

#### Segurança de Senhas

O framework utiliza `password_hash` com algoritmo `PASSWORD_ARGON2ID` para hash de senhas:

```php
$senhaHash = password_hash($senha, PASSWORD_ARGON2ID);
```

Validação:

```php
if (password_verify($senha, $senhaHash)) {
    // Senha válida
}
```

#### Proteção contra SQL Injection

O framework utiliza prepared statements do PDO em todas as consultas, protegendo contra SQL injection.

#### Proteção CSRF

Para implementar proteção CSRF, adicione tokens nas requisições POST e valide-os nos controllers.

---

## Persistência de Dados

### Query Builder

O repositório fornece um query builder fluente:

```php
$usuarios = $repository
    ->filterBy(['ativo' => true])
    ->filterBy([['name' => 'nome', 'operator' => 'CONTEM', 'value' => 'João']])
    ->orderBy('nome', 'ASC')
    ->limit(10)
    ->offset(0)
    ->get();
```

### Joins

```php
$repository->addJoin('oasys', 'perfis', 'id', 'usuario_id', 'LEFT', 'p');
$dados = $repository->get();
```

### Paginação

```php
$page = 1;
$perPage = 10;

$repository->filterBy(['ativo' => true]);
$total = $repository->count();
$usuarios = $repository->paginate($perPage, $page);
```

### Operações CRUD

#### Create

```php
$usuario = new Usuario();
$usuario->setNome('João');
$usuario->setEmail('joao@example.com');
$repository->save($usuario);
```

#### Read

```php
// Por ID
$usuario = $repository->findBy('id', 1);

// Com filtros
$usuarios = $repository->filterBy(['ativo' => true])->get();
```

#### Update

```php
$usuario = $repository->findBy('id', 1);
$usuario->setNome('João Silva');
$repository->save($usuario);
```

#### Delete

```php
$usuario = $repository->findBy('id', 1);
$repository->remove($usuario);
```

---

## Exemplos de Uso

### Exemplo Completo: CRUD de Produtos

#### 1. Model (Produto.php)

```php
<?php

namespace Framework\Interface\Domain\Produto;

use Framework\Infrastructure\MVC\Model\Model;

class Produto extends Model
{
    protected ?int $id;
    protected ?string $nome;
    protected ?float $preco;
    protected ?string $descricao;

    // Getters e Setters
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }
    public function getNome(): ?string { return $this->nome; }
    public function setNome(?string $nome): void { $this->nome = $nome; }
    public function getPreco(): ?float { return $this->preco; }
    public function setPreco(?float $preco): void { $this->preco = $preco; }
    public function getDescricao(): ?string { return $this->descricao; }
    public function setDescricao(?string $descricao): void { $this->descricao = $descricao; }
}
```

#### 2. Repository (ProdutoRepository.php)

```php
<?php

namespace Framework\Interface\Infrastructure\Persistence\Produto;

use Framework\Infrastructure\DB\Persistence\Repository\Repository;
use Framework\Interface\Domain\Produto\Produto;

class ProdutoRepository extends Repository
{
    protected function getModelClass(): string
    {
        return Produto::class;
    }

    protected function getSchema(): ?string
    {
        return 'oasys';
    }

    protected function getTableName(): string
    {
        return 'produtos';
    }
}
```

#### 3. FormController (ProdutoFormController.php)

```php
<?php

namespace Framework\Interface\Infrastructure\Controllers\Produto;

use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Interface\Infrastructure\Persistence\Produto\ProdutoRepository;
use Framework\Interface\Infrastructure\View\Produto\ProdutoFormView;

class ProdutoFormController extends FormController
{
    protected function getViewClass(): string
    {
        return ProdutoFormView::class;
    }

    protected function getRepositoryClass(): string
    {
        return ProdutoRepository::class;
    }

    protected function beforeAdd($model)
    {
        parent::beforeAdd($model);
        // Validações
        if (empty($model->getNome())) {
            throw new \Exception('Nome é obrigatório');
        }
    }
}
```

#### 4. GridController (ProdutoGridController.php)

```php
<?php

namespace Framework\Interface\Infrastructure\Controllers\Produto;

use Framework\Infrastructure\MVC\Controller\GridController;
use Framework\Interface\Infrastructure\Persistence\Produto\ProdutoRepository;
use Framework\Interface\Infrastructure\View\Produto\ProdutoGridView;

class ProdutoGridController extends GridController
{
    protected function getViewClass(): string
    {
        return ProdutoGridView::class;
    }

    protected function getRepositoryClass(): string
    {
        return ProdutoRepository::class;
    }
}
```

#### 5. Rotas no Banco de Dados

```sql
-- Rota para listagem
INSERT INTO rotas (nome, caminho, metodo, pacote, titulo) 
VALUES ('produto_list', 'ProdutoGridController', 'list', 'Produto', 'Listagem de Produtos');

-- Rota para formulário de adição
INSERT INTO rotas (nome, caminho, metodo, pacote, titulo) 
VALUES ('produto_add', 'ProdutoFormController', 'add', 'Produto', 'Adicionar Produto');

-- Rota para formulário de edição
INSERT INTO rotas (nome, caminho, metodo, pacote, titulo) 
VALUES ('produto_edit', 'ProdutoFormController', 'edit', 'Produto', 'Editar Produto');
```

---

## Boas Práticas

### 1. Nomenclatura

- **Controllers**: Use sufixo `Controller` (ex: `UsuarioFormController`)
- **Repositories**: Use sufixo `Repository` (ex: `UsuarioRepository`)
- **Models**: Use nome singular (ex: `Usuario`)
- **Views**: Use sufixo `View` (ex: `UsuarioFormView`)

### 2. Organização de Código

- Mantenha a separação de responsabilidades (MVC)
- Use namespaces adequados
- Documente métodos complexos
- Evite lógica de negócio nos controllers

### 3. Segurança

- Sempre valide dados de entrada
- Use prepared statements (já implementado)
- Proteja rotas sensíveis
- Valide permissões de usuário
- Use HTTPS em produção

### 4. Performance

- Use eager loading com `with()` para evitar N+1 queries
- Implemente cache quando necessário
- Use índices no banco de dados
- Otimize consultas complexas

### 5. Tratamento de Erros

```php
try {
    // Operação
} catch (\Framework\Infrastructure\Exceptions\Mensagem $e) {
    // Erro esperado do framework
    $this->setAvisoRetorno($e->getMessage(), Aviso::TIPO_ERRO);
} catch (\Exception $e) {
    // Erro inesperado
    if (Main::isAmbienteDesenvolvimento()) {
        throw $e;
    }
    $this->setAvisoRetorno('Erro ao processar solicitação');
}
```

---

## Troubleshooting

### Problema: Erro de conexão com banco de dados

**Solução**: Verifique as configurações em `Main::setBdConfig()` e certifique-se de que o banco de dados está acessível.

### Problema: Token JWT inválido

**Solução**: Verifique se a chave secreta em `General::$SECRET_JWT` está correta e se o token não expirou.

### Problema: Rota não encontrada

**Solução**: Verifique se a rota existe no banco de dados na tabela `rotas` e se o nome da rota corresponde ao que está sendo solicitado.

### Problema: Erro ao salvar relacionamentos

**Solução**: Certifique-se de que os relacionamentos estão configurados corretamente no repositório e que as foreign keys existem no banco de dados.

---

## Conclusão

O OAsys Framework fornece uma base sólida para desenvolvimento de aplicações web em PHP. Esta documentação cobre os aspectos principais do framework. Para mais informações, consulte o código-fonte ou entre em contato com a equipe de desenvolvimento.

---

## Changelog

### Versão 1.0.0
- Lançamento inicial do framework
- Sistema MVC completo
- Autenticação JWT
- Sistema de repositórios
- Componentes de interface
- Sistema de migrações

---

## Licença

[Especificar licença do projeto]

---

## Contato

**Desenvolvedor**: Iago Oliveira  
**Email**: prog.iago.oliveira@gmail.com

---

**Última atualização**: Março 2025

