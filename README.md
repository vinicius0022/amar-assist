
# 📦 Laravel + Vue + Docker – Gerenciador de Produtos

Aplicação web para cadastro e gerenciamento de produtos, com frontend em Vue.js, backend Laravel e ambiente em Docker.

---

## 🚀 Como subir o projeto

### Pré-requisitos

- [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/) instalados.

### 1. Subir containers

Execute o comando abaixo para levantar os containers:

```bash
docker-compose up -d
```

A aplicação será acessível em:

- **Frontend (Vue)**: [http://localhost:5173](http://localhost:5173)
- **Backend (Laravel API)**: [http://localhost:8000](http://localhost:8000)

Verifique as portas no seu arquivo `docker-compose.yml` caso use outras.

---

## 🧱 Configuração inicial do backend (Laravel)

### 1. Acessar o container

```bash
docker exec -it laravel-app bash
```

### 2. Instalar dependências

```bash
composer install
```

### 3. Criar arquivo `.env`

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Rodar as migrations e seeders

```bash
php artisan migrate --seed
```

Isso irá criar as tabelas e inserir dados de exemplo.

---

## 🖼️ Uso da aplicação

- **Cadastro de produtos**: Através da interface do Vue, é possível cadastrar produtos com as seguintes informações:
  - **Título**
  - **Descrição** (com HTML limitado: apenas `<p>`, `<br>`, `<b>`, `<strong>`)
  - **Preço de venda** (campo numérico)
  - **Custo** (campo numérico)
  - **Campo ativo** (booleano: `true` ou `false`)
  - **Imagens** (suporta upload de múltiplas imagens)

- **Edição de produtos**:
  - Atualizar os campos do produto.
  - Remover imagens existentes.
  - Adicionar novas imagens.

---

## 🔐 Autenticação

- A autenticação é feita utilizando Laravel Sanctum.
- **Primeiro**, o frontend faz uma requisição para obter o `csrf-cookie`.
- **Em seguida**, o login é feito, e a rota `/api/me` retorna os dados do usuário autenticado.

---

## 🧪 Testes unitários

### Executar testes:

```bash
docker-compose exec app php artisan test
```

**Testes existentes**:

- **`tests/Feature/ProductTest.php`**:
  - Testa criação de produto.
  - Validação de campos obrigatórios.
  - Validação de upload de imagens.
  - Testa atualização de produto com imagens.
  - Testa exclusão de imagem.

---

## 📋 Regras de negócios do Produto

### Campos obrigatórios

| Campo        | Tipo     | Regras                                             |
|--------------|----------|----------------------------------------------------|
| title        | string   | obrigatório, máx. 255 caracteres                   |
| description  | string   | obrigatório, só permite `<p>`, `<br>`, `<b>`, `<strong>` |
| sale_price   | decimal  | obrigatório, >= 0, máx. 999999.99                  |
| cost         | decimal  | obrigatório, >= 0, máx. 999999.99                  |
| active       | boolean  | obrigatório (true/false)                           |
| images       | arquivo  | jpg ou png, máx. 2MB cada imagem                   |

### Validações personalizadas

- **HTML permitido na descrição**: apenas `<p>`, `<br>`, `<b>`, `<strong>`.
- **`sale_price`** e **`cost`** são validados para número entre `0` e `999999.99`.
- **Imagens** podem ser deletadas durante a edição de um produto.

---

## 🛠️ Scripts úteis

### Limpar cache e permissões

Caso haja algum problema com cache ou permissões, execute os seguintes comandos:

```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### Subir containers Docker novamente

Se precisar parar e iniciar novamente os containers, use:

```bash
docker-compose down
docker-compose up -d --build
```

---

## 📬 Contato

Em caso de dúvidas, sugestões ou bugs, abra uma *issue* ou entre em contato.

---
