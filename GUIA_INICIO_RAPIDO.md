# Guia de Início Rápido - OAsys Framework

Este guia fornece um tutorial passo a passo para criar sua primeira funcionalidade no OAsys Framework.

## Pré-requisitos

- Framework instalado e configurado
- Banco de dados configurado
- Tabela `rotas` criada no banco de dados

## Passo 1: Criar a Tabela no Banco de Dados

```sql
CREATE TABLE exemplo_produtos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2),
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Passo 2: Criar o Model

Crie o arquivo `src/Interface/Domain/Produto/Produto.php`:

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

    public function getPreco(): ?float
    {
        return $this->preco;
    }

    public function setPreco(?float $preco): void
    {
        $this->preco = $preco;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }
}
```

## Passo 3: Criar o Repository

Crie o arquivo `src/Interface/Infrastructure/Persistence/Produto/ProdutoRepository.php`:

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
        return 'exemplo_produtos';
    }
}
```

## Passo 4: Criar o FormController

Crie o arquivo `src/Interface/Infrastructure/Controllers/Produto/ProdutoFormController.php`:

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

## Passo 5: Criar o GridController

Crie o arquivo `src/Interface/Infrastructure/Controllers/Produto/ProdutoGridController.php`:

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

## Passo 6: Criar as Views

### FormView

Crie o arquivo `src/Interface/Infrastructure/View/Produto/ProdutoFormView.php`:

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
        $fieldDescricao = new FormField('descricao', 'Descrição');
        
        $form->addField($fieldNome);
        $form->addField($fieldPreco);
        $form->addField($fieldDescricao);
        
        $this->setViewComponent($form);
    }

    protected function create()
    {
        // Configurações adicionais se necessário
    }

    public function render()
    {
        // Implementar renderização
        // Geralmente retorna JSON ou HTML
    }
}
```

### GridView

Crie o arquivo `src/Interface/Infrastructure/View/Produto/ProdutoGridView.php`:

```php
<?php

namespace Framework\Interface\Infrastructure\View\Produto;

use Framework\Infrastructure\MVC\View\Interface\GridView;
use Framework\Infrastructure\MVC\View\Components\Grid\Grid;
use Framework\Infrastructure\MVC\View\Components\Fields\GridField;

class ProdutoGridView extends GridView
{
    protected function instanciaViewComponent()
    {
        $grid = new Grid();
        
        $fieldId = new GridField('id', 'ID');
        $fieldNome = new GridField('nome', 'Nome');
        $fieldPreco = new GridField('preco', 'Preço');
        $fieldDescricao = new GridField('descricao', 'Descrição');
        
        $grid->addColumn($fieldId);
        $grid->addColumn($fieldNome);
        $grid->addColumn($fieldPreco);
        $grid->addColumn($fieldDescricao);
        
        $this->setViewComponent($grid);
    }

    protected function create()
    {
        // Configurações adicionais se necessário
    }

    public function render()
    {
        // Implementar renderização
        // Geralmente retorna JSON ou HTML
    }
}
```

## Passo 7: Registrar as Rotas no Banco de Dados

Execute os seguintes comandos SQL:

```sql
-- Rota para listagem
INSERT INTO rotas (nome, caminho, metodo, pacote, titulo) 
VALUES ('produto_list', 'ProdutoGridController', 'list', 'Produto', 'Listagem de Produtos');

-- Rota para adicionar
INSERT INTO rotas (nome, caminho, metodo, pacote, titulo) 
VALUES ('produto_add', 'ProdutoFormController', 'add', 'Produto', 'Adicionar Produto');

-- Rota para editar
INSERT INTO rotas (nome, caminho, metodo, pacote, titulo) 
VALUES ('produto_edit', 'ProdutoFormController', 'edit', 'Produto', 'Editar Produto');

-- Rota para deletar
INSERT INTO rotas (nome, caminho, metodo, pacote, titulo) 
VALUES ('produto_delete', 'ProdutoFormController', 'delete', 'Produto', 'Deletar Produto');
```

## Passo 8: Testar a Funcionalidade

1. Acesse a rota `produto_list` para ver a listagem
2. Acesse a rota `produto_add` para adicionar um produto
3. Acesse a rota `produto_edit?id=1` para editar um produto
4. Use a ação de deletar para remover um produto

## Próximos Passos

- Adicione validações mais complexas
- Implemente relacionamentos entre modelos
- Adicione filtros personalizados no grid
- Customize as views conforme necessário
- Adicione permissões de acesso

## Dicas

1. **Use os hooks do controller**: `beforeAdd`, `afterAdd`, `beforeEdit`, etc.
2. **Implemente validações**: Sempre valide os dados antes de salvar
3. **Use relacionamentos**: Aproveite o sistema de relacionamentos do framework
4. **Customize as views**: Adapte as views às suas necessidades
5. **Documente seu código**: Mantenha o código documentado

## Problemas Comuns

### Erro: "Classe não encontrada"
**Solução**: Execute `composer dump-autoload` para atualizar o autoload.

### Erro: "Rota não encontrada"
**Solução**: Verifique se a rota foi cadastrada corretamente no banco de dados.

### Erro: "Token inválido"
**Solução**: Verifique se você está autenticado e se o token JWT é válido.

### Erro: "Tabela não encontrada"
**Solução**: Verifique se a tabela existe no banco de dados e se o nome está correto no repository.

## Recursos Adicionais

- [Documentação Completa](./DOCUMENTACAO.md)
- [README](./README.md)
- Código-fonte do framework

---

**Bom desenvolvimento!** 🚀

