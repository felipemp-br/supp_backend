
# SUPP TESTES

## Execução
```bash
$ sudo docker-compose exec php-dev make run-tests
```

## Testes Automatizados

Os testes devem seguir o padrão desenvolvido no diretório devido,
lembrando que sempre deve-se avaliar a necessidade da criação do teste e que pode ter mais 
ou menos complexidade que os exemplos.

### Functional
Os testes funcionais simulam uma execução real da aplicação, 
podendo ser desde uma requisição na API ou um Job por linha de comando.

| Tipo da classe   | Diretório                           | Funcionalidade                                                                                      |
-------------------|-------------------------------------|-----------------------------------------------------------------------------------------------------|
| `Command`        | `tests/Functional/Command/`         | Testes de execução via linha de comando.                                                            |
| `Controller`     | `tests/Functional/Api/V1/Controller/`      | Testes na API, fazendo requisições em todos os EndPoints do `Controller` específico.                |

### Unit
Testes unitários testam somente a classe específica e seus métodos internos.

| Tipo da classe  | Diretório                           | Funcionalidade                                      |
-------------------|-------------------------------------|-----------------------------------------------------|
| `Rules`          | `tests/Unit/Api/V1/Rules/`          | Teste unitário de uma `Rule` específica.            |                  
| `Triggers`       | `tests/Unit/Api/V1/Triggers/`       | Teste unitário de uma `Trigger` específica.         | 


### Integration
Os testes de integração verificam se camadas específicas da aplicação estão trabalhando de forma correta entre si.

| Tipo da classe   | Diretório                           | Funcionalidade                                                 |
-------------------|-------------------------------------|----------------------------------------------------------------|
| `Controller`     | `tests/Integration/Api/V1/Controller/`     | Verifica se o controller está utilizando a `Resource` correta. |
| `DTO`            | `tests/Integration/Api/V1/DTO/`            | Verifica se o DTO está de acordo com a `Entidade`.             |

##  Banco de dados e DataFixtures

Com a necessidade de testes que consomem o banco de dados, foi criado um banco separado por ambiente, sendo utilizado o mySQL para desenvolvimento e SQLite para os testes.

Para carregar os dados "fake" é utilizado `DataFixtures`, eles ficam no 
diretório `src/DataFixtures/ORM` e são divididos por grupos:

| Ambiente     |                                                                        |
---------------|------------------------------------------------------------------------|
|`prod`        | São carregados sempre, tabelas base para funcionamento do sistema.    |
|`dev`         | São carregados somente no ambiente de desenvolvimento no banco `MySQL`.|
|`test`        | São carregados somente do ambiente de testes no banco `SQLite`.        |   

Dependendo do teste, será preciso carregar uma ou mais DataFixtures, 
é possível carregar `DataFixtures` específicas para um teste como no `AfastamentoControllerTest` ,
ou um grupo específico, o parâmetro aceita o nome da Fixture ou de um Grupo.  
Lembrando que para cada teste o banco é restaurado.

## Relatório de cobertura de código ##

Após a execução dos testes é gerado um relatório de cobertura de código no seguinte diretório:

```build/logs/coverage```
