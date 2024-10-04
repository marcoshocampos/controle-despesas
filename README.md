# Projeto de API - Gerenciamento de Despesas

Este projeto é uma API desenvolvida em **Laravel** para gerenciamento de despesas, permitindo aos usuários autenticados criar, ler, atualizar e excluir despesas. A API implementa autenticação, políticas de acesso, notificações e validação de dados.

## Funcionalidades

- **Autenticação de Usuário**: Acesso protegido ao CRUD de despesas utilizando autenticação via tokens.
- **CRUD de Despesas**: Operações de criação, leitura, atualização e exclusão da entidade "Despesa".
- **Restrição de Acesso**: Apenas o usuário vinculado à despesa pode acessá-la ou modificá-la.
- **Notificação**: O usuário é notificado via e-mail ao cadastrar uma nova despesa.

## Estrutura da Entidade "Despesa"

- **Id**: Identificador único da despesa.
- **Descrição**: Detalhamento da despesa (até 191 caracteres).
- **Data**: Data em que a despesa ocorreu (não pode ser uma data futura).
- **Valor**: Valor da despesa (não pode ser negativo).
- **Usuário**: Relacionamento com o usuário dono da despesa.

## Requisitos de Validação

- Verificar se o usuário existe.
- Impedir cadastro de despesas com datas futuras.
- Garantir que o valor da despesa não seja negativo.
- Limitar a descrição da despesa a 191 caracteres.

## Endpoints

### 1. **Listar Despesas**
- **Método**: `GET /api/despesas`
- **Descrição**: Retorna todas as despesas associadas ao usuário autenticado.

### 2. **Criar Despesa**
- **Método:** `POST /api/despesas`
- **Descrição**: Cria uma nova despesa vinculada ao usuário autenticado.

### 3. **Exibir Despesa**

- **Método:** `GET /api/despesas/{id}`
- **Descrição:** Retorna os detalhes de uma despesa específica.

**Parâmetro:**
- **id** (identificador da despesa).

### 4. **Atualizar Despesa**

- **Método:** `PUT /api/despesas/{id}`
- **Descrição**: Atualiza uma despesa vinculada ao usuário autenticado.

**Parâmetro:**
- **id** (identificador da despesa).

### 5. **Excluir Despesa**

- **Método:** `DELETE /api/despesas/{id}`
- **Descrição:** Deleta uma despesa associada ao usuário autenticado.

**Parâmetro:**
- **id** (identificador da despesa).

### 6. **Registro de Usuário**

- **Método:** `POST /api/register`
- **Descrição:** Criar novo usuário.

### 7. **Login**

- **Método:** `POST /api/login`
- **Descrição:** Autentica o usuário credenciado corretamente.

### 8. **Logout**

- **Método:** `POST /api/logout`
- **Descrição:** Autentica o usuário credenciado corretamente.


A documentação da API foi gerada utilizando a ferramenta Swagger e está disponível para consulta no seguinte endpoint:
  ```bash
    /api/doc
  ```
Essa documentação descreve todas as rotas, parâmetros, métodos HTTP e as respostas esperadas para as APIs do sistema. Ela é essencial para auxiliar no desenvolvimento e integração com o sistema, garantindo que os desenvolvedores entendam claramente o funcionamento da API.

## Instalação e Configuração

Clone o repositório:
```bash
    git clone git@github.com:marcoshocampos/onfly-teste.git
```

### Instale as dependências

1. No diretório do projeto, execute o seguinte comando para instalar as dependências do projeto:
  ```bash
    composer install
  ```

2. Configure o arquivo .env para incluir as credenciais de banco de dados, configurações de e-mail e outras variáveis necessárias para o funcionamento correto da aplicação. Pode usar o comando abaixo para utilizar o .env.example como referência:
  ```bash
    cp .env.example .env
  ```

3. Inicie a aplicação:
  ```bash
    php artisan serve
  ```

4. Para configurar o banco de dados, execute as migrações utilizando o comando abaixo
  ```bash
    php artisan migrate
  ```

5. Caso queira popular o banco com alguns exemplos, pode rodar o comando abaixo para utilizar os seeders
  ```bash
    php artisan db:seed
  ```

6. Para processar as notificações de email que forem adicionadas a fila, execute o seguinte comando:
  ```bash
    php artisan queue:work
  ```
Para testar o envio dos emails em ambiente de desenvolvimento, foi utilizado o [Mailtrap](https://mailtrap.io)

## Testes Unitários

Os testes unitários foram implementados para garantir a qualidade e o correto funcionamento das seguintes funcionalidades:

- **Autenticação de Usuário**: Verifica a autenticação correta e os fluxos de login/logout.
- **Operações de CRUD de Despesas**: Valida as operações de criação, leitura, atualização e exclusão de despesas no sistema.
- **Restrições de Acesso com Policies**: Garante que apenas usuários autorizados possam acessar ou modificar determinados recursos, de acordo com as regras de Policies definidas.

Para executar os testes unitários e validar o comportamento do sistema, utilize o seguinte comando:
  ```bash
    php artisan test
  ```
