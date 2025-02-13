# Pastelaria COMERC

## 📌 Sobre o Projeto
Esta projeto foi desenvolvido em Laravel para API e Vue.js (Quasar Framework) para o Frontend com o intuíto de gerenciar pedidos de uma pastelaria. O sistema permite a criação e gerenciamento de clientes, produtos e pedidos, além de envio de e-mails e integração com um banco de dados MySQL.

---

## 🛠️ Tecnologias Utilizadas
- **Laravel 11** (Backend)
- **MySQL 9.1** (Banco de Dados)
- **Docker** (Ambiente de Desenvolvimento)
- **Postman** (Testes de API)
- **PHPUnit** (Testes Unitários)
- **Vue.js: - Quasar Framework** (Frontend) - TypeScript e Composition API com script setup

---

## 🚀 Como Configurar e Rodar a Aplicação

### 🔧 Pré-requisitos
Certifique-se de ter instalado:
- Docker e Docker-Compose
- Git

### 📂 Clonando o Repositório
```sh
 git clone https://github.com/GuiBodelon/desafio-comerc.git
 cd ./pastelaria-api
```

### 🔑 Configurando Variáveis de Ambiente
Edite o `.env` para configurar o banco de dados:
```env

# Banco de Dados
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=pastelaria_prod
DB_DATABASE_TEST=pastelaria_test
DB_USERNAME=root
DB_PASSWORD=rootpassword
DB_CHARSET=utf8
DB_COLLATION=utf8_general_ci

# Nginx
NGINX_HOST=localhost
NGINX_PORT=80

# Laravel
APP_NAME=Pastelaria
APP_ENV=local
APP_KEY=base64:tg3/QbZO0XAa49J/JT6mp7bnS83wS8BvDesiB9f9dz8=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

# SMTP - Envio de e-mails (Dados reais e funcionais)
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=desafio-pastelaria@frontendeveloper.com.br
MAIL_PASSWORD=@Pastel123
MAIL_FROM_ADDRESS="desafio-pastelaria@frontendeveloper.com.br"
MAIL_FROM_NAME="${APP_NAME}"
```

### 🐳 Rodando com Docker
1. **Construir e iniciar os containers:**
   ```sh
   docker-compose up --build -d
   ou
   docker-compose up --force-recreate -d
   ```

2. **Gerar a chave da aplicação (se necessário):**
   ```sh
   docker-compose exec app php artisan key:generate
   ```

3. **Rodar as migrações e seeders (se necessário):**
   ```sh
   docker-compose exec app php artisan migrate:fresh --seed
   ```

---

## 🔍 Testando a API

### 📌 Testando via Postman
A API está rodando em `http://127.0.0.1:8000/api/`.

- **Importe a coleção Postman:** [https://web.postman.co/workspace/1290e8e5-f6b6-4ffa-8c58-5f28ce1c3846](#)
- Utilize os seguintes endpoints para testar as funcionalidades:
  - **Clientes:** `GET /customers`, `POST /customers`, etc.
  - **Produtos:** `GET /products`, `POST /products`, etc.
  - **Pedidos:** `GET /orders`, `POST /orders`, etc.

### ✅ Rodando Testes Unitários
Para rodar os testes PHPUnit:
```sh
docker-compose exec app php artisan test
```
Se desejar rodar um teste específico (CustomerTest, OrderTest ou ProductTest):
```sh
docker-compose exec app php artisan test --filter NomeDoTeste
```

---