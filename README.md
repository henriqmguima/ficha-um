# 📋 Ficha Um

**Ficha Um** é um sistema de gerenciamento de filas para atendimentos em unidades básicas de saúde, desenvolvido com CodeIgniter 4. Ele permite o registro de unidades, criação de fichas de atendimento, controle de status e visualização da posição na fila em tempo real por parte dos usuários.

A inciativa do projeto veio através de suprir uma demanda do pronto atendimento das unidades de saúde pública que utilizam o SUS de Charqueadas/RS

---

## 🚀 Funcionalidades Principais

### Para o Cidadão (Paciente)

- Solicitação de ficha digital remotamente.
- Visualização da posição na fila em tempo real.
- Acompanhamento do status do atendimento (aguardando → acolhido → chamado → atendido).
- Atualização automática via JavaScript.

### Para Diretores / Administração

- Gestão de unidades de saúde (postos).
- Criação de usuários: diretores, médicos e pacientes.
- Geração de fichas digitais
- Visualização de fila de acordo com o tempo de espera
- Triagem das fichas: sinais vitais, sintomas e prioridade (Manchester).
- Encaminhamento automático para o médico com menor carga de atendimentos.

### Para Médicos

- Visualização de fichas já triadas com destaque na prioridade e tempo de espera.
- Visualização de detalhes do caso da ficha.
- Chamada de pacientes.
- Encerramento de atendimentos.
- Contador diário de atendimentos.

### API RESTful

- Criar ficha digital via API.
- Retornar a ficha ativa do usuário.
- Listagem completa para debugging/integração.
- Permite futura integração com aplicativos móveis.

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
- Habilitar as extensões intl e mysqli no php.ini

---

## 🧪 Instalação e Execução

1. **Clone o repositório**

```bash
git clone https://github.com/henriqmguima/ficha-um
cd ficha-um
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

Crie o banco de dados `sistema_filas` na sua máquina

Execute os seguintes comandos no terminal do projeto:

```bash
php spark migrate --all
php spark db:seed PostosSeeder
php spark db:seed UsuariosSeeder
php spark db:seed FichaSeeder
```

5. **Execute o servidor**

```bash
php spark serve
```

Acesse: [http://localhost:8080](http://localhost:8080)

---

## 👥 Acesso ao Sistema (Seeders)

Os seeders criam dezenas de usuários reais.

## 📌 Perfis criados automaticamente

> **Diretor** de cada posto | login: `10000000001` — senha: `123456`

> **Admin** por posto | login: `20000000001` — senha: `123456`

> 2 **Médicos** por posto | login: `30000000001` — senha: `123456`

> 5 **Pacientes** por posto | login: `40000000001` — senha: `123456`

Total: 10 Diretores, 10 Admin, 20 Médicos e 50 Pacientes.
Para logar em um usuário diferente, mas de mesmo tipo, basta aumentar um algarismo do último digito.
`10000000002`, `10000000003`, `10000000004`, por exemplo.

✔ Você terá usuários de todos os perfis já prontos para usar.

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
