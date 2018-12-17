# Relatórios
API RESTful de serviço de relatórios para uma petshop. Projeto desenvolvido para a cadeira de Desenvolvimento de Componentes e Serviços da Faculdade SENAC RS.

## Heroku

A API está disponível na seguinte URL: https://petshop-api-reports.herokuapp.com/api/v1/report/customers/1

## Como rodar em desenvolvimento

- Clonar o repositório.
- Criar um banco de dados para a aplicação.
- Renomear o arquivo .env.example para .env e inserir as informações do banco de dados.
- Executar `composer install` para instalar as dependências.
- Executar `php artisan migrate` para rodar as migrações do banco.
- Executar `php artisan db:seed` para criar os registros fake.
- Executar `php -S localhost:8000 -t public` para rodar o servidor local.
- Abrir no navegador http://localhost:8000 ou o endereço que o console indicar.

## Endpoints

Todos os endpoints estão englobados com `/api/v1`. Exemplo: `localhost:8000/api/v1`.

| Método HTTP | Rota                  | Descrição                               |
| ----------- | --------------------- | --------------------------------------- |
| GET         | /report/product/{id}  | Retorna o relatório do produto {id}     |
| GET         | /report/customer/{id} | Retorna o relatório do cliente {id}     |


### Swagger
A definição da API está disponível na seguinte URL: https://app.swaggerhub.com/apis-docs/EduardoUmpierre/petstore_pedidos/1.0.0
