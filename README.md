# MyFinance ERP API

Este projeto é uma API em Laravel desenvolvida para gerenciar operações financeiras e cadastros de um sistema ERP de pequeno porte. Ele foi pensado como um projeto de portfólio para demonstrar habilidades em backend, modelagem de dados, autenticação, permissões, rotas REST e integração com banco de dados.

## O que o sistema faz

A aplicação permite controlar os principais módulos de um ambiente financeiro e comercial, incluindo:

- Cadastro de clientes, produtos e serviços
- Gestão de vendas
- Controle de formas de pagamento
- Lançamentos financeiros a receber e a pagar
- Despesas recorrentes
- Gestão de usuários e permissões
- Estrutura de autenticação e autorização para acesso às rotas

## Tecnologias utilizadas

- PHP 8.1+
- Laravel 10
- MySQL
- Composer
- Node.js / Vite
- Spatie Laravel Permission
- Sanctum

## Requisitos prévios

Antes de iniciar, certifique-se de ter instalado em sua máquina:

- PHP 8.1 ou superior
- Composer
- Node.js e npm
- MySQL ou outro banco compatível
- Git

## Como executar o projeto em outra máquina

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

### 2. Instale as dependências do PHP

```bash
composer install
```

### 3. Instale as dependências do front-end

```bash
npm install
```

### 4. Configure o ambiente

Crie o arquivo de ambiente a partir do exemplo:

```bash
copy .env.example .env
```

Em seguida, edite o arquivo .env e ajuste as configurações do banco de dados:

```env
APP_NAME=MyFinanceERP
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myfinance_erp
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 6. Crie o banco de dados

Crie manualmente o banco no MySQL com o nome configurado em DB_DATABASE.

### 7. Execute as migrations e seeders

```bash
php artisan migrate --seed
```

### 8. Inicie a aplicação

```bash
php artisan serve
```

A API ficará disponível em:

```text
http://127.0.0.1:8000
```

### 9. Execute os assets do front-end (se necessário)

```bash
npm run dev
```

## Estrutura principal do projeto

- app/Http/Controllers: controllers da API
- app/Models: modelos do domínio
- database/migrations: estrutura do banco
- database/seeders: dados iniciais
- routes/api.php: endpoints da aplicação

## Próximos passos

Algumas melhorias que podem ser adicionadas no futuro:

- Implementação de testes automatizados
- Front-end completo para gestão do ERP
- Dashboard financeiro com gráficos
- Exportação de relatórios
- Melhorias na experiência de autenticação e permissões

## Licença

Este projeto pode ser utilizado como base para estudo e portfólio. Consulte o arquivo de licença do projeto para mais detalhes.