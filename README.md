Aqui está o arquivo README.md formatado corretamente para você copiar e colar:

---

# Pastelaria COMERC

## 📌 Sobre o Projeto

Este projeto foi desenvolvido em Laravel para API e Vue.js (Quasar Framework) para o Frontend com o intuito de gerenciar pedidos de uma pastelaria. O sistema permite a criação e gerenciamento de clientes, produtos e pedidos, além de envio de e-mails e integração com um banco de dados MySQL.

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
```

### 🔑 Configurando Variáveis de Ambiente

Edite o `.env` para configurar o banco de dados e outras variáveis de ambiente:

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

# SMTP - Envio de e-mails (Dados reais e funcionais do meu Host)
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
   ```

   Ou

   ```sh
   docker-compose up --force-recreate -d
   ```

2. **Gerar a chave da aplicação (se necessário):**

   ```sh
   docker-compose exec php php artisan key:generate
   ```

3. **Rodar as migrações e seeders (se necessário):**
   ```sh
   docker-compose exec php php artisan migrate:fresh --seed
   ```

---

## 🔍 Testando a API

### 📌 Testando via Postman

A API está rodando em `http://127.0.0.1:8080/api/` dentro do container.
E também foi exposta em `http://127.0.0.1:9000/api/`.

- **Importe a coleção Postman:** [Link para a Coleção](https://web.postman.co/workspace/1290e8e5-f6b6-4ffa-8c58-5f28ce1c3846)
- Utilize os seguintes endpoints para testar as funcionalidades:
  - **Clientes:** `GET /customers`, `POST /customers`, etc.
  - **Produtos:** `GET /products`, `POST /products`, etc.
  - **Pedidos:** `GET /orders`, `POST /orders`, etc.

### 📌 Teste de Login (Usuário de Teste)

Para acessar a área administrativa e testar os CRUDLs, use as credenciais abaixo para realizar o login:

- **Email:** testuser@example.com
- **Senha:** password123
- Acesse a rota principal `/` para logar.

### 📌 Teste de Criação de Pedido (Cliente)

Se deseja testar a criação de um pedido como se fosse um cliente, faça o seguinte:

1. **Processo sem autenticação:**
   - No sistema, o cliente pode acessar a rota `/pedidos` diretamente para criar um pedido.
2. **Envia os dados do pedido** através da rota `POST /pedidos` com o corpo de requisição contendo os seguintes parâmetros:

   - **customer_id**: ID do cliente (pode ser o mesmo do login ou outro).
   - **products**: Array de produtos, onde cada produto contém:
     - **product_id**: ID do produto.
     - **quantity**: Quantidade do produto desejado.

### 📧 E-mails de Teste para Criação de Pedidos

Caso ainda não tenha registrado um e-mail próprio, você pode utilizar os seguintes e-mails de teste para criar novos pedidos e testar a funcionalidade:

- **ana.oliveira@example.com**
- **carlos.santos@example.com**
- **juliana.almeida@example.com**
- **felipe.lima@example.com**
- **marcela.costa@example.com**

Esses e-mails já estão pré-cadastrados no seeder para facilitar o processo de teste. Você pode usá-los para simular a criação de pedidos e verificar o comportamento do sistema.

---

### ✅ Rodando Testes Unitários

Para rodar os testes PHPUnit:

```sh
docker-compose exec php php artisan test
```

Se desejar rodar um teste específico (CustomerTest, OrderTest ou ProductTest):

```sh
docker-compose exec php php artisan test --filter NomeDoTeste
```

---

## ⚠️ Caso de Erro na Criação dos Containers (Meu Docker é bugado desde sempre, portanto ja vou deixar alguns comando para caso ocorra contigo. Mas as vezes a culpa nem é do Docker, o problema pode estar entre a cadeira e o monitor né)

Se você encontrar o erro:

```
failed to solve: archive/tar: unknown file mode ?rwxr-xr-x
```

Durante a criação dos containers, rode o comando abaixo na raiz do projeto Laravel para corrigir o problema:

```sh
rm public/storage
```

---

## 💡 Notas

Por conta do tempo limitado, algumas funcionalidades adicionais não foram implementadas, tais como:

- **Segurança mais robusta:** Implementar verificações mais detalhadas e autenticação para cada cliente, mantendo os dados do cliente salvos em `localStorage` para uso recorrente.
- **Níveis de acesso:** Criar níveis de acesso diferenciados para usuários do sistema e clientes. A tabela `user` e `customer` seria unificada, permitindo que o cliente realize login no sistema e tenha acesso às funcionalidades conforme seu nível de autorização.
- **UX/UI:** Convenhamos, está pessimo kkkkk
- **Outros:** E claro, um projeto real também incluiria testes de integração e testes de carga. A implementação de um sistema de logs detalhados para identificar possíveis falhas. Também seria interessante incluir um Buscador de CEP para preenchimento automático do endereço melhorando a usabilidade, corrigir possíveis bugs, e também uma forma mais completa de iniciar o projeto criando scripts que automatizam o processo de configuração e inicialização do projeto tanto para desenvolvimento quanto para testes e produção.

---
