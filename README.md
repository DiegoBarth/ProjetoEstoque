# 📦 ProjetoEstoque

![PHP](https://img.shields.io/badge/PHP-8.1-blue?logo=php)
![Laravel](https://img.shields.io/badge/laravel-10-red?logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-42b883?logo=vue.js&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-blue?logo=postgresql)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.3-38B2AC?logo=tailwind-css&logoColor=white)
![License MIT](https://img.shields.io/badge/license-MIT-brightgreen)
![Status](https://img.shields.io/badge/status-em%20desenvolvimento-yellow)

Sistema web completo para **loja de eletrônicos**, com foco em **atendimento, controle de vendas, estoque e gestão de metas**.  
Desenvolvido com **Laravel 10**, **Tailwind CSS** e **PostgreSQL**, o sistema permite que atendentes realizem vendas rapidamente e que gestores acompanhem resultados por meio de **gráficos e relatórios personalizados**.

> 🔐 Ideal para uso em balcão e gestão de loja: controle de estoque em tempo real, registro de vendas e acompanhamento de metas mensais.

---

## 📚 Índice

- [� ProjetoEstoque](#-projetoestoque)
  - [📚 Índice](#-índice)
  - [🚀 Funcionalidades](#-funcionalidades)
    - [👥 Atendimento e Operação](#-atendimento-e-operação)
    - [📊 Gestão e Relatórios](#-gestão-e-relatórios)
  - [🛠️ Tecnologias utilizadas](#️-tecnologias-utilizadas)
  - [📦 Exemplo de uso real](#-exemplo-de-uso-real)
  - [⚙️ Instalação e uso](#️-instalação-e-uso)
  - [🖥️ Backend (Laravel + PostgreSQL)](#️-backend-laravel--postgresql)
  - [🎨 Frontend (Tailwind CSS + Vite)](#-frontend-tailwind-css--vite)
  - [✅ Requisitos](#-requisitos)
  - [🧑‍💻 Autores](#-autores)
  - [📄 Licença](#-licença)

---

## 🚀 Funcionalidades

### 👥 Atendimento e Operação
- Registro de **vendas (saídas)** pela tela de atendimento
- Cadastro de **entradas de produtos**
- Cadastro e edição de **produtos**, **fornecedores** e **clientes**
- Autenticação e controle de acesso

### 📊 Gestão e Relatórios
- 📈 **Visualização de gráficos** (vendas, estoque, metas)
- 🎯 Cadastro de **metas mensais ou por categoria**
- 📄 Emissão de **relatórios gerenciais** (ex: vendas por período, produtos mais vendidos)
- Histórico de movimentações

---

## 🛠️ Tecnologias utilizadas

| Camada | Ferramenta |
|--------|------------|
| Backend | Laravel 10 (PHP 8.1+) |
| Frontend | Vue.js + Tailwind CSS |
| Banco de Dados | PostgreSQL |
| ORM | Eloquent |
| Build frontend | Vite |
| Gerenciadores | Composer / npm |
| Visualização de dados | Charts.js (ou outro se aplicável) |

---

## 📦 Exemplo de uso real

1. A loja cadastra os produtos e fornecedores
2. O atendente registra uma venda pela tela de **atendimento**
3. O estoque é automaticamente atualizado
4. O gestor acompanha o desempenho por meio de **relatórios e gráficos**
5. As **metas cadastradas** são comparadas com os resultados reais mês a mês

---

## ⚙️ Instalação e uso

1. Clone o repositório:

```bash
git clone https://github.com/DiegoBarth/ProjetoEstoque.git
cd ProjetoEstoque
```

## 🖥️ Backend (Laravel + PostgreSQL)

1. Acesse a pasta do backend:

```bash
cd backend
```

2. Instale as dependências PHP:

```bash
composer install
```

3. Copie o arquivo .env e configure:

```bash
cp .env.example .env
```

4. Atualize as variáveis do banco de dados no .env:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=projeto_estoque
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. Gere a chave da aplicação:

```bash
php artisan key:generate
```

6. Rode as migrations:

```bash
php artisan migrate
```

7. Inicie o servidor do backend:

```bash
php artisan serve
```

## 🎨 Frontend (Tailwind CSS + Vite)

1. Em outra aba do terminal, acesse a pasta frontend:

```bash
cd ../frontend
```

2. Instale as dependências Node:

```bash
npm install
```

3. Inicie o servidor do frontend:

```bash
npm run dev
```

## ✅ Requisitos

- PHP 8.1 ou superior
- Composer
- PostgreSQL
- Node.js (opcional, caso queira compilar o Tailwind com Vite)

## 🧑‍💻 Autores

Diego Barth
https://github.com/DiegoBarth

Kauã Rodrigo
https://github.com/KauaRodrigo

## 📄 Licença

Este projeto está sob a licença MIT.
