
# 📋 Ficha Um

**Ficha Um** é um sistema de gerenciamento de filas para atendimentos em unidades básicas de saúde, desenvolvido com CodeIgniter 4. Ele permite o registro de unidades, criação de fichas de atendimento, controle de status e visualização da posição na fila por parte dos usuários.

A inciativa do projeto veio através de suprir uma demanda do pronto atendimento das unidades de saúde pública que utilizam o SUS de Charqueadas/RS

---

## 🚀 Funcionalidades Principais

- Registro de unidades de saúde
- Cadastro de Diretor Geral, administradores e usuários comuns (pacientes)
- Criação e controle de fichas de atendimento
- Atualização do status das fichas (aguardando, em atendimento, atendido)
- Visualização da posição na fila em tempo real
- API RESTful para integração externa

---

## 🛠️ Tecnologias Utilizadas

- PHP 8+
- CodeIgniter 4
- MySQL
- HTML5 + CSS3 (com design responsivo)
- JavaScript (Fetch API)
- Bootstrap

---

## ⚙️ Requisitos

- PHP 8.1+
- Composer
- MySQL

---

## 🧪 Instalação e Execução

1. **Clone o repositório**

```bash
git clone https://github.com/henriqmguima/gerenciamento-de-fila-para-sa-de
cd gerenciamento-de-fila-para-sa-de
```

2. **Instale as dependências**

```bash
composer install
```

3. **Configure o ambiente**

Crie um arquivo `.env` com base no `.env.example` abaixo e configure o acesso ao banco de dados:

```
database.default.hostname = localhost
database.default.database = sistema_filas
database.default.username = root
database.default.password = root
database.default.DBDriver = MySQLi
```

4. **Crie o banco de dados e rode as Demos**

```bash
php spark migrate
php spark db:seed DemoSeeder
```

5. **Execute o servidor**

```bash
php spark serve
```

Acesse: [http://localhost:8080](http://localhost:8080)

---

## 👥 Acesso ao sistema

- **Administrador 1:** `00011122233` — senha: `admin123`
- **Administrador 2:** `00011122244` — senha: `admin123`
- **Usuários comuns:** `11122233301`, `11122233302` ... até `11122233305`

> A senha de todos os usuários comuns é `usuario123`

---

## 📁 Estrutura do Projeto

- `app/Controllers`: Lógica dos controladores (Ficha, Usuário, API e frontend)
- `app/Models`: Models com regras de acesso ao banco
- `app/Views`: Telas HTML renderizadas com dados dinâmicos
- `app/Database/Seeds`: Seeders para popular o sistema
- `app/Database/Migrations`: Migrations para estrutura do banco de dados

---

## 🧑‍💻 Autoria

Desenvolvido por Henrique Guimarães como parte de trabalho de conclusão de curso — 2025.
