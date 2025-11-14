# OAsys Framework

Framework PHP moderno baseado em arquitetura MVC para desenvolvimento de aplicações web robustas e escaláveis.

## 🚀 Características

- ✅ Arquitetura MVC completa
- ✅ Sistema de rotas dinâmico baseado em banco de dados
- ✅ Autenticação JWT segura
- ✅ ORM abstrato com repositórios
- ✅ Sistema de relacionamentos (1:1, 1:N, N:N)
- ✅ Componentes de interface reutilizáveis (Grid, Form, Kanban, Dashboard)
- ✅ Sistema de migrações de banco de dados
- ✅ Logs automáticos de operações CRUD
- ✅ Suporte multi-tenant
- ✅ Query Builder fluente

## 📋 Requisitos

- PHP 8.0 ou superior
- Extensões PHP: `pdo`, `fileinfo`, `gd`
- Composer
- PostgreSQL ou MySQL
- Servidor Web (Apache/Nginx)

## 📦 Instalação

```bash
# Clone o repositório
git clone <repository-url>
cd OAsys-Framework

# Instale as dependências
composer install
```

## ⚙️ Configuração Rápida

### 1. Configure o Banco de Dados

```php
use Framework\Core\Main;

Main::setBdConfig([
    'dsn' => 'pgsql:host=localhost;dbname=oasys_db',
    'user' => 'usuario',
    'password' => 'senha'
]);
```

### 2. Configure o Ambiente

```php
Main::setConfig([
    'ambiente' => 'DEV' // ou 'QA' ou 'PROD'
]);
```

### 3. Configure a Chave JWT

Edite `src/Auth/General.php`:

```php
static $SECRET_JWT = 'sua_chave_secreta_aqui';
```

## 📚 Documentação

Para documentação completa, consulte [DOCUMENTACAO.md](./DOCUMENTACAO.md).

## 🏗️ Estrutura Básica

```
OAsys-Framework/
├── src/
│   ├── Auth/              # Autenticação JWT
│   ├── Core/              # Núcleo do framework
│   ├── Infrastructure/    # Infraestrutura (MVC, DB, CLI)
│   └── Interface/         # Camada de interface
├── vendor/                # Dependências
└── composer.json
```

## 💡 Exemplo de Uso

### Criando um Model

```php
use Framework\Infrastructure\MVC\Model\Model;

class Produto extends Model
{
    protected ?int $id;
    protected ?string $nome;
    
    // Getters e Setters
}
```

### Criando um Repository

```php
use Framework\Infrastructure\DB\Persistence\Repository\Repository;

class ProdutoRepository extends Repository
{
    protected function getModelClass(): string
    {
        return Produto::class;
    }
    
    protected function getTableName(): string
    {
        return 'produtos';
    }
}
```

### Criando um Controller

```php
use Framework\Infrastructure\MVC\Controller\FormController;

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
}
```

## 🔐 Autenticação

```php
use Framework\Auth\Autenticator;

$autenticator = new Autenticator($email, $senha, $repository);

if ($autenticator->login()) {
    $token = $autenticator->generateToken();
    $_SESSION['oasys-token'] = $token;
}
```

## 📖 Guia Rápido

1. **Crie seu Model** estendendo `Model`
2. **Crie seu Repository** estendendo `Repository`
3. **Crie seu Controller** estendendo `FormController` ou `GridController`
4. **Crie sua View** estendendo `FormView` ou `GridView`
5. **Registre a rota** no banco de dados

## 🤝 Contribuindo

Contribuições são bem-vindas! Por favor, leia as diretrizes de contribuição antes de submeter pull requests.

## 📄 Licença

[Especificar licença]

## 👤 Autor

**Iago Oliveira**
- Email: prog.iago.oliveira@gmail.com

## 📝 Changelog

Veja [CHANGELOG.md](./CHANGELOG.md) para detalhes das versões.

---

Para mais informações, consulte a [Documentação Completa](./DOCUMENTACAO.md).

