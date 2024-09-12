# Sistema de Autenticação com Dois Fatores (2FA)

Este projeto é uma aplicação em Laravel que implementa um sistema de autenticação com autenticação de dois fatores (2FA), permitindo uma camada extra de segurança.

O sistema exige que os usuários insiram um código de verificação gerado por um aplicativo autenticador, como o Google Authenticator, além das credenciais padrão (e-mail e senha).

## Funcionalidades

- **Registro de Usuário:** Permite o registro de novos usuários.
- **Autenticação com Dois Fatores (2FA):** Após o login com e-mail e senha, se o usuário tiver 2FA habilitado, será solicitado um código 2FA.
- **Lembrete de Senha:** Envio de e-mails para redefinição de senha.
- **Proteção de Rotas com 2FA:** As rotas protegidas exigem autenticação de dois fatores para usuários habilitados.

## Pré-requisitos

- PHP >= 8.1
- Composer
- Node.js (para compilação dos assets)
- Banco de Dados MySQL (ou outro compatível com Laravel)
- Aplicativo de Autenticação como Google Authenticator ou Authy

## Instalação

1. **Clone o repositório:**

   ```bash
   git clone git@github.com:carolinealtoss/authentication-system.git
   cd authentication-system
   ```

2. **Instale as dependências do Composer:**

   ```bash
   composer install
   ```

3. **Instale as dependências do Node.js:**

   ```bash
   npm install
   ```

4. **Copie o arquivo `.env.example` para `.env`:**

   ```bash
   cp .env.example .env
   ```

5. **Configure o banco de dados no arquivo `.env`:**

   Abra o arquivo `.env` e configure as credenciais do banco de dados:

   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nome_do_banco
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

6. **Gere a chave da aplicação:**

   ```bash
   php artisan key:generate
   ```

7. **Execute as migrações para criar as tabelas no banco de dados:**

   ```bash
   php artisan migrate
   ```

8. **Compile os assets de frontend:**

   ```bash
   npm run dev
   ```

9. **Inicie o servidor de desenvolvimento:**

   ```bash
   php artisan serve
   ```

   Acesse a aplicação em `http://localhost:8000`.

## Configurando o Google 2FA

1. **Instalação do Google2FA:**
   O pacote `pragmarx/google2fa-laravel` já está instalado no projeto e configurado.

2. **Ativar o 2FA para um usuário:**
   Após registrar um usuário, navegue para o painel do usuário e siga as instruções para configurar o Google Authenticator.

   - Escaneie o código QR gerado pelo sistema no aplicativo Google Authenticator.
   - O código gerado deve ser inserido no campo de verificação para completar a configuração.

## Executando os Testes

1. **Testes de Unidade e Funcionalidade:**

   O projeto usa PHPUnit para testes. Para rodar os testes de unidade e de funcionalidade, execute:

   ```bash
   php artisan test
   ```

2. **Testar Funcionalidades Específicas:**

   Se quiser testar uma funcionalidade específica, por exemplo, o login, você pode rodar:

   ```bash
   php artisan test --filter NomeDoTeste
   ```

## Estrutura do Projeto

- `app/Models/User.php`: Contém a lógica do modelo de usuário, incluindo métodos relacionados ao 2FA.
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`: Controlador responsável pela lógica de login e autenticação.
- `app/Http/Controllers/TwoFactorController.php`: Controlador responsável pela verificação do código 2FA.
- `resources/views/auth/`: Contém as views para o login e verificação do 2FA.
- `routes/web.php`: Define as rotas principais, incluindo as rotas de verificação de 2FA.

## Tecnologias Utilizadas

- **Laravel 11:** Framework PHP para desenvolvimento web.
- **Pragmarx Google2FA:** Biblioteca para autenticação de dois fatores com o Google Authenticator.
- **Bootstrap:** Framework CSS para estilização do frontend.
- **MySQL:** Banco de dados relacional.
- **PHPUnit:** Para testes automatizados.

## Licença

Este projeto é open-source e está sob a licença [MIT](https://opensource.org/licenses/MIT).
