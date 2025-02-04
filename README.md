# Sistema de Gestão de Projetos e Time Tracking - Documentação Completa

## Índice
1. [Visão Geral](#1-visão-geral)
2. [Estrutura do Banco de Dados](#2-estrutura-do-banco-de-dados)
3. [Fluxo de Telas](#3-fluxo-de-telas)
4. [Arquitetura Frontend](#4-arquitetura-frontend)
5. [Arquitetura Backend](#5-arquitetura-backend)
6. [Autenticação](#6-autenticação)
7. [Regras de Negócio](#7-regras-de-negócio)
8. [Permissões e Acessos](#8-permissões-e-acessos)

## 1. Visão Geral

### 1.1 Stack Tecnológica
- Frontend: Next.js 14+ com TypeScript
- Backend: Laravel (API RESTful)
- UI Components: shadcn/ui
- Autenticação: Laravel Breeze + NextAuth.js
- Banco de Dados: MySQL/PostgreSQL

### 1.2 Principais Funcionalidades
- Gestão de Clientes
- Gestão de Projetos
- Controle de Tempo
- Faturamento
- Gestão de Colaboradores
- Relatórios

## 2. Estrutura do Banco de Dados

### 2.1 Schema SQL Completo

```sql
-- Users (Base para autenticação)
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'collaborator') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Clients
CREATE TABLE clients (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(255) NOT NULL,
    trade_name VARCHAR(255),
    document_number VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(255),
    phone VARCHAR(20),
    default_hour_rate DECIMAL(10,2),
    status ENUM('active', 'inactive') DEFAULT 'active',
    billing_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Collaborators (extends users)
CREATE TABLE collaborators (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    hour_rate DECIMAL(10,2) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    bank_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Projects
CREATE TABLE projects (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    client_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE NOT NULL,
    end_date DATE,
    status ENUM('active', 'paused', 'completed', 'archived') DEFAULT 'active',
    hour_rate DECIMAL(10,2),
    billing_type ENUM('client_rate', 'project_rate') DEFAULT 'client_rate',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Project Collaborators (Many-to-Many)
CREATE TABLE project_collaborators (
    project_id BIGINT NOT NULL,
    collaborator_id BIGINT NOT NULL,
    role ENUM('leader', 'member') DEFAULT 'member',
    hour_rate DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (project_id, collaborator_id),
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (collaborator_id) REFERENCES collaborators(id)
);

-- Sprints
CREATE TABLE sprints (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    project_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('planning', 'in_progress', 'completed') DEFAULT 'planning',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id)
);

-- Tasks
CREATE TABLE tasks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    project_id BIGINT NOT NULL,
    sprint_id BIGINT,
    parent_task_id BIGINT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('backlog', 'todo', 'in_progress', 'review', 'done') DEFAULT 'backlog',
    estimated_hours DECIMAL(10,2),
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    assignee_id BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (sprint_id) REFERENCES sprints(id),
    FOREIGN KEY (parent_task_id) REFERENCES tasks(id),
    FOREIGN KEY (assignee_id) REFERENCES collaborators(id)
);

-- Task Attachments
CREATE TABLE task_attachments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    task_id BIGINT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50),
    file_size INT,
    uploaded_by BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

-- Task Comments
CREATE TABLE task_comments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    task_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Time Tracking
CREATE TABLE time_entries (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    task_id BIGINT NOT NULL,
    collaborator_id BIGINT NOT NULL,
    description TEXT,
    start_time DATETIME NOT NULL,
    end_time DATETIME,
    duration_minutes INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (collaborator_id) REFERENCES collaborators(id)
);

-- Sprint Billings
CREATE TABLE sprint_billings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    sprint_id BIGINT NOT NULL,
    total_hours DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'billed', 'paid') DEFAULT 'pending',
    invoice_number VARCHAR(50),
    invoice_date DATE,
    payment_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sprint_id) REFERENCES sprints(id)
);

-- Collaborator Payments
CREATE TABLE collaborator_payments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    sprint_id BIGINT NOT NULL,
    collaborator_id BIGINT NOT NULL,
    total_hours DECIMAL(10,2) NOT NULL,
    amount_due DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'paid') DEFAULT 'pending',
    payment_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sprint_id) REFERENCES sprints(id),
    FOREIGN KEY (collaborator_id) REFERENCES collaborators(id)
);
```

## 3. Fluxo de Telas

### 3.1 Estrutura de Navegação
```
├── Login/Auth
├── Dashboard Principal
│   ├── Overview Projetos
│   ├── Tasks Pendentes
│   └── Tempo Hoje
├── Área de Clientes
│   ├── Lista de Clientes
│   ├── Detalhes do Cliente
│   └── Faturamento Cliente
├── Gestão de Projetos
│   ├── Lista de Projetos
│   ├── Kanban Board
│   ├── Detalhes do Projeto
│   └── Time Tracking
├── Área do Colaborador
│   ├── Minhas Tasks
│   ├── Meu Tempo
│   └── Meus Pagamentos
└── Área Financeira
    ├── Faturamento
    └── Relatórios
```

### 3.2 Componentes Principais de Cada Tela

#### Dashboard
- Overview Cards
- Gráfico de Horas
- Lista de Tasks Pendentes
- Calendário de Sprints

#### Área de Clientes
- Tabela de Clientes
- Formulário de Cliente
- Histórico de Projetos
- Relatório Financeiro

#### Gestão de Projetos
- Kanban Board
- Timer de Task
- Formulário de Task
- Lista de Sprints

#### Área Financeira
- Dashboard Financeiro
- Relatórios de Faturamento
- Controle de Pagamentos

## 4. Arquitetura Frontend

### 4.1 Estrutura de Diretórios Next.js
```
src/
├── app/                    
│   ├── (auth)/            
│   │   ├── dashboard/     
│   │   ├── clients/       
│   │   ├── projects/      
│   │   ├── collaborators/ 
│   │   └── reports/       
│   ├── api/               
│   └── auth/              
├── components/            
│   ├── ui/               
│   ├── forms/            
│   ├── layout/           
│   ├── dashboard/        
│   ├── projects/         
│   └── shared/           
├── lib/                  
│   ├── api/              
│   ├── auth/             
│   ├── utils/            
│   └── hooks/            
├── store/                
│   ├── slices/           
│   └── services/         
└── styles/               
    ├── globals.css       
    └── theme/            
```

### 4.2 Configuração do Next-Auth
```typescript
// app/lib/auth.ts
import { NextAuthOptions } from "next-auth"
import CredentialsProvider from "next-auth/providers/credentials"

export const authOptions: NextAuthOptions = {
  session: {
    strategy: 'jwt'
  },
  providers: [
    CredentialsProvider({
      name: 'Credentials',
      credentials: {
        email: { label: "Email", type: "email" },
        password: { label: "Password", type: "password" }
      },
      async authorize(credentials) {
        // Implementação da autenticação
      }
    })
  ],
  callbacks: {
    async jwt({ token, user, account }) {
      if (account && user) {
        return {
          ...token,
          accessToken: user.token
        }
      }
      return token
    },
    async session({ session, token }) {
      session.user.accessToken = token.accessToken
      return session
    }
  }
}
```

## 5. Arquitetura Backend

### 5.1 Estrutura Laravel
- Controllers para cada entidade
- Resources para transformação de dados
- Policies para autorização
- Jobs para processamento async
- Events para notificações

### 5.2 Configuração CORS
```php
// config/cors.php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

## 6. Autenticação

### 6.1 Fluxo de Login
1. Usuário submete credenciais no frontend
2. Next-auth envia para API Laravel
3. Laravel valida e retorna token
4. Token armazenado na sessão Next-auth
5. Token usado em requisições subsequentes

### 6.2 Middleware de Proteção
```typescript
// middleware.ts
export default withAuth(
  function middleware(req) {
    return NextResponse.next()
  },
  {
    callbacks: {
      authorized: ({ token }) => !!token
    },
  }
)

export const config = {
  matcher: [
    "/dashboard/:path*",
    "/projects/:path*",
    "/clients/:path*",
    "/collaborators/:path*",
  ]
}
```

## 7. Regras de Negócio

### 7.1 Clientes
- Taxa hora opcional
- Status controla criação de projetos
- Histórico de faturamento mantido

### 7.2 Projetos
- Taxa hora própria ou do cliente
- Mínimo um colaborador
- Sprints de 15 dias

### 7.3 Time Tracking
- Mínimo 30 minutos por task
- Arredondamento 15 minutos
- Validação de sobreposição

### 7.4 Faturamento
- Cálculo ao fim da sprint
- Valores diferenciados por colaborador
- Relatórios separados

## 8. Permissões e Acessos

### 8.1 Níveis de Acesso
- Admin: Acesso total
- Manager: Gestão sem financeiro
- Collaborator: Acesso limitado

### 8.2 Restrições por Nível
#### Admin
- Gestão completa
- Dados financeiros
- Configurações

#### Manager
- Gestão de projetos
- Visualização de custos
- Relatórios básicos

#### Collaborator
- Suas tasks
- Seu tempo
- Seus pagamentos

## 9. Próximos Passos

1. Setup do Ambiente
   - Configurar Next.js
   - Configurar Laravel
   - Configurar Banco de Dados

2. Desenvolvimento Base
   - Aut
