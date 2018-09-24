# Serviço de agendamento
API RESTful de serviço de agendamento para uma petshop. Projeto desenvolvido para a cadeira de Desenvolvimento de Componentes e Serviços da Faculdade SENAC RS.

## Heroku

A API está disponível na seguinte URL: https://petshop-api.herokuapp.com/api/v1/schedule

## Como rodar em desenvolvimento

- Clonar o repositório.
- Executar `composer install` para instalar as dependências.
- Executar `php artisan migrate` para rodar as migrações do banco.
- Executar `php artisan db:seed` para criar os registros fake.
- Executar `php -S localhost:8000 -t public` para rodar o servidor local.
- Abrir no navegador http://localhost:8000 ou o endereço que o console indicar.

## Endpoints

Todos os endpoints estão englobados com `/api/v1`. Exemplo: `localhost:8000/api/v1`.

| Método HTTP | Rota           | Descrição                               |
| ----------- | -------------- | --------------------------------------- |
| GET         | /schedule      | Lista todos os agendamentos             |
| GET         | /schedule/{id} | Retorna os detalhes do agendamento {id} |
| POST        | /schedule      | Insere um agendamento                   |
| PUT         | /schedule/{id} | Edita o agendamento {id}                |
| DELETE      | /schedule/{id} | Remove o agendamento {id}               |

### Campos obrigatórios (POST e PUT)

- ID do usuário
- Data: DateTime no formato (yyyy-MM-dd HH:mm:ss)

Exemplo:

```
{
  "users_id": 1,
  "date": "2018-09-12 12:00:00" 
}
```
