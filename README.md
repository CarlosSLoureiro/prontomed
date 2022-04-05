
# 👨‍⚕️ ProntoMed

> Aplicação desenvolvida para seleção da [PEBMED](https://pebmed.com.br).

ProntoMed é um sistema de prontuário eletrônico feito com [Laravel 9.2](https://laravel.com) onde o médico pode cadastrar as informações do paciente e fazer os registros das consultas realizadas por paciente.

# ✅ Guia de Controle

- [x] Diagrama de Entidade Relacional
- [x] Banco de Dados
- [x] PHP
- [x] Restful
- [x] Regras de negócio
- [x] JSON Web Token
- [x] Testes
- [x] Postman
- [x] Docker

# 💻 Pré-requisitos

Antes de começar, caso vc queira editar e compilar o webpack (scss e javascript) dessa aplicação, você precisará ter o [NodeJS](https://nodejs.org/pt-br) (npm) e as devidas dependências instalados em sua máquina. Ademais, caso você opte por não utilizar o [Docker](https://www.docker.com) para executar a aplicação, você também precisará do [Composer](https://getcomposer.org), [PHP](https://www.php.net) e do [MySQL](https://www.mysql.com).
> Versões utilizadas para desenvolver a aplicação: PHP 8.1.4, MySQL 8.0.26, Composer 2.2.9 e NodeJS v16.10.0 (npm 8.5.5).

# 🚀 Instalando

Para instalar o **ProntoMed**, siga estas etapas:

1. Faça um clone ou o download do código fonte desse repositório.
2. Abra o cmd ou terminal integrado da sua IDE no diretório do repositório.
3. Caso você **vá** utilizar o Docker, suba a aplicação:
```
    docker-compose up -d
```
4. Após subir a aplicação, use o bash do container da aplicação:
```
    docker exec -it prontomed-app bash
```
5. Com ou sem o Docker, copie o arquivo `.env.example` para `.env`:
```
    cp .env.example .env
```
6. Caso você **não vá** utilizar o Docker, configure as credenciais do seu banco de dados no arquivo `.env` e crie o banco de dados `prontomed`.

7. Faça o download das dependências do composer:
```
    composer install
```
8. Gere uma key:
```
    php artisan key:generate
```
9. Gere uma key para o JWT:
```
    php artisan jwt:secret
```
10. Teste o código **(opcional)**: 
```
    ./vendor/bin/phpunit
```
- `Resultado esperado:`
```
    OK (37 tests, 105 assertions)
```
11. Faça a migração (envie o banco de dados):
```
    php artisan migrate
```
12. Crie os dados fakes de exemplo:
```
    php artisan db:seed
```
- `Atenção:` Caso você queira cadastrar apenas o usuário administrador:
```
    php artisan db:seed --class=MedicoAdminSeeder
```

13. Caso você **não esteja** utilizando Docker, inicie a aplicação:
```
    php artisan serve
```

14. Abra seu navegador e acesse `http://127.0.0.1:8000/`.
- Usuário: `admin@prontomed.com`
- Senha: `med-admin000`

15. Explore o sistema. 😊

# ✔️ [Postman](https://www.postman.com/flight-specialist-65767632/workspace/prontomed/collection/20220169-4194991e-6b6f-4725-8732-009ec17a6e9d?ctx=documentation)
- **Autenticação** (Login, Carregar Dados, Logout)
- **Pacientes** (Listar, Cadastrar, Editar, Excluir)
- **Consultas** (Listar, Cadastrar, Reagendar, Adicionar Observação, Excluir)

# 📈 Diagrama de Entidade Relacional
<img src="https://i.imgur.com/VA7JOxS.png" alt="Diagrama ER">

# 📝 Licença

Esse projeto está sob licença. Veja o arquivo [LICENÇA](LICENSE) para mais detalhes.

[⬆ Voltar ao topo](#)