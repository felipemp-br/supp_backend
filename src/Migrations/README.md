Passos para gerar uma nova realase e o arquivo de Migration

1. Gerar no supp-core/supp-administrativo-frontend um branch de
   release.
```bash
$ git checkout develop
$ git pull origin 
$ git checkout -b release/1.15.0
$ git push --set-upstream origin release/1.15.0
```

2. Recuperar a imagem store/oracle/database-enterprise:12.2.0.1
   do banco de dados oracle presente no Docker Hub (https://hub.docker.com/).
   Fazer o pull da imagem e subir o container.

```bash
# sudo docker login usuario
$ sudo docker pull diefaustino/oracle:12.2.0.1
$ sudo docker-compose up oracledb
```

3. Dentro do container executar os seguintes comandos no banco de
   banco dados criando o usuario e atribuindo as grants necessarias.
   Voce terá criado com estes comandos um schema vazio dentro de um
   banco Oracle.

```bash
$ sudo docker exec -it oracledb bash -c "source /home/oracle/.bashrc; sqlplus /nolog"
CONNECT SYS AS SYSDBA; # password: Oradoc_db1
ALTER SESSION SET "_ORACLE_SCRIPT"=true;
CREATE USER SUPP IDENTIFIED BY supp;
GRANT ALL PRIVILEGES TO supp;   
```

4. Fazer o checkout do repositório na tag anterior (no nosso
   exemplo 1.14.0)
```bash
# para recuperar todas as tags
$ git fetch --all --tags 

# para ver as 10 tags mais recentes 
$ git tag --sort=-v:refname | head -10

v1.6.10
v1.6.9
1.15.0
1.14.0 <<
1.13.0
1.12.0
1.11.0
1.10.0
1.9.3
1.9.2
 
$ git checkout tags/1.14.0 -b tmp/1.14.0
```
5. Recriar o ambiente nesta versão. Será criado um banco na opção padrão
   de banco default: MySQL. Comentar a linha que popula o banco no arquivo
   docker/dev/php/docker-entrypoint.sh, pois você deseja apenas o schema
   e não o conteúdo das tabelas.

```bash
# remover pacotes eventualmente instalados
$ sudo rm -rf vendor/* 

# recriar o ambiente
$ sudo docker-compose build php-dev 

# comentar a abaixo linha do aquivo docker/dev/php/docker-entrypoint.sh 
# que popula as tabelas do banco de dado (dispara as features) 
# php /app/bin/console doctrine:fixtures:load --em default --append --group dev --no-interaction

# subir o ambiente - talvez algumas alteracoes sejam necessarias no
# composer.json para pacotes que apontam para dev-master e tenham
# sido alterados (e as exigencias tenham mudado) 
$ sudo docker-compose up php-dev 

# o banco foi criado na versao anterior. agora temos de voltar 
# para a versao atual PRESERVANDO O BANCO CRIADO
```

6. Depois de subir o container o schema no banco já foi criado e o container
   php-dev pode ser interrompido. Fazer as alterações necessárias para subir novamente
   o container php-dev, mas agora **apontando para o banco de dados Oracle**.
   Alterar o arquivo .env na raiz do projeto setando as seguintes variáveis.

**ATENÇÃO: SE VOCÊ TIVER UM ARQUIVO .env.local, AS CONFIGURAÇÕES DESTE
ARQUIVO DE SOBREPÕEM ÀS DO ARQUIVO .env. NESTE CASO VOCÊ DEVERÁ ALTERAR
ESTAS VARIÁVEIS NESTE ARQUIVO TAMBÉM**

```dotenv
# arquivo .env
DATABASE_URL=oci8://supp:supp@oracledb:1521/ORCLCDB.localdomain
DATABASE_SERVICE=true
```

7. Alterar o arquivo AbstractOracleDriver que fica no diretório
   \Doctrine\DBAL\Driver:
```php
    // De:
    protected function getEasyConnectString(array $params)
    {
        return (string) EasyConnectString::fromConnectionParameters($params);
    }
    
    // Para:
    protected function getEasyConnectString(array $params)
    {
        return '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=oracledb)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=ORCLCDB.localdomain)))';
    }
```

8. No docker-entrypoint.sh comentar a linha que destrói (drop) e cria
   (create) o schema de banco.  Comentar também a instalação de pacotes
   pelo composer e a execução das fixtures (ver abaixo).

```bash
# aquivo docker/dev/php/docker-entrypoint.sh

# COMENTAR AS LINHAS ABAIXO
#composer -V
#composer install --ansi --no-interaction

...

# COMENTAR AS LINHAS ABAIXO
# ATENÇÃO: APENAS A LINHA QUE ATUALIZA O SCHEMA FICA ATIVA 
#php /app/bin/console doctrine:database:drop --connection default --force --no-interaction
#php /app/bin/console doctrine:database:create --connection default --no-interaction
php /app/bin/console doctrine:schema:update --em default  --force --no-interaction
#php /app/bin/console doctrine:fixtures:load --em default --append --group dev --no-interaction
```

9. Subir novamente o container. O schema será criado agora dentro do banco Oracle.

```bash
$ rm -rf var/cache
$ sudo docker-compose up php-dev
```

10. O banco na versão antiga já foi gerado nos dois bancos (Oracle e MySQL).
    O container php-dev pode ser interrompido para a recuperação da release atual.

**ATENÇÃO: NESTA ETAPA VOCÊ IRÁ APENAS RECRIAR O CONTAINER (BUILD) MAS NÃO IRÁ SUBIR POIS
ISTO REESCREVERIA OS SCHEMAS DE BANCO SALVOS.**

```bash
$ git checkout --force release/1.15.0
$ sudo rm -rf vendor/* 
$ sudo docker-compose build php-dev

```

11. Subir o container php-dev na versão corrente preservando o banco
    criado na versão anterior. Para isto **ANTES DE SUBIR O AMBIENTE**
    você deverá comentar as linhas do docker-entrypoint.sh.

```bash
# aquivo docker/dev/php/docker-entrypoint.sh

# COMENTAR AS LINHAS ABAIXO  
# O SCHEMA CRIADO NO PASSO ANTERIOR NÃO PODE SER ALTERADO 
#php /app/bin/console doctrine:database:drop --connection default --force --no-interaction
#php /app/bin/console doctrine:database:create --connection default --no-interaction
#php /app/bin/console doctrine:schema:update --em default  --force --no-interaction
#php /app/bin/console doctrine:fixtures:load --em default --append --group dev --no-interaction
#php /app/bin/console ongr:es:index:create --index=pessoa --if-not-exists --no-interaction -vvv
#php /app/bin/console ongr:es:index:create --index=modelo --if-not-exists --no-interaction
#php /app/bin/console ongr:es:index:create --index=repositorio --if-not-exists --no-interaction
#php /app/bin/console ongr:es:index:create --index=processo --if-not-exists --no-interaction
#php /app/bin/console ongr:es:index:create --index=tese --if-not-exists --no-interaction
#php /app/bin/console ongr:es:template:create --index=componente_digital --if-not-exists --no-interaction
#php /app/bin/console ongr:es:pipeline:create --index=componente_digital --no-interaction
#php /app/bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulatePessoaMessage" --startId=1 --endId=30 --batch=5 --no-interaction
#php /app/bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateModeloMessage" --startId=1 --endId=30 --batch=5 --no-interaction
#php /app/bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateProcessoMessage" --startId=1 --endId=30 --batch=5 --no-interaction
#php /app/bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateTeseMessage" --startId=1 --endId=30 --batch=5 --no-interaction
#php /app/bin/console ongr:es:index:create --index=comunicacao_judicial --if-not-exists --no-interaction
#php /app/bin/console ongr:es:index:populate --message="SuppCore\JudicialBackend\Command\Elastic\Messages\PopulateComunicacaoJudicialMessage" --startId=1 --endId=10 --batch=5 --no-interaction

# subir o ambiente na versao atualizada **sem criação de banco** 
sudo docker-compose up php-dev 
```

12. Rodar dentro do container php-dev os comandos de migration para extrair
    a diferença entre as versões. Este conteúdo será o migrations **para o MySQL**.
```bash
$ sudo docker exec -it php-dev bash
$ bin/console doctrine:migrations:diff                                                                                                                        
 [WARNING] You have 37 available migrations to execute.                                                                 
 Are you sure you wish to continue? (yes/no) [yes]:
 > yes

 Generated new migration class to "/app/src/Migrations/Version20240223161711.php"
 To run just this migration for testing purposes, you can use migrations:execute --up 'DoctrineMigrations\\Version20240223161711'
 To revert the migration you can use migrations:execute --down 'DoctrineMigrations\\Version20240223161711'

```

13. Revisar e editar o arquivo gerado. No caso do exemplo, foi gerado
    o arquivo Version20240223161711.php dentro do diretório src/Migrations/.
    Incluir neste arquivo um desvio condicional para as versões de Banco de Dados
    e colocar o conteúdo editado **dentro do braço do MySQL**.

```php
   public function up(Schema $schema): void
    {
        // incluir este desvio condicional
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // Vazio por enquanto...
        } else {
            // Aqui vai o codigo gerado e revisado para MySQL            
        }
    }
    
    public function down(Schema $schema): void
    {
        // incluir este desvio condicional
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // Vazio por enquanto...
        } else {
            // Aqui vai o codigo gerado e revisado para MySQL            
        }
    }

```
14. Interromper o container php-dev. Fazer as alterações necessárias
    para subir novamente o container php-dev, mas agora **apontando para
    o container Oracle**. Alterar o arquivo .env na raiz do projeto
    setando as seguintes variáveis.

**ATENÇÃO: SE VOCÊ TIVER UM ARQUIVO .env.local, AS CONFIGURAÇÕES DESTE
ARQUIVO DE SOBREPÕEM ÀS DO ARQUIVO .env. NESTE CASO VOCÊ DEVERÁ ALTERAR
ESTAS VARIÁVEIS NESTE ARQUIVO TAMBÉM**

```dotenv
# arquivo .env
DATABASE_URL=oci8://supp:supp@oracledb:1521/ORCLCDB.localdomain
DATABASE_SERVICE=true
```

15. Comentar as linhas que instalam os pacotes no arquivo docker/dev/php/docker-entrypoint.sh:

```bash
# aquivo docker/dev/php/docker-entrypoint.sh

# COMENTAR AS LINHAS ABAIXO
#composer -V
#composer install --ansi --no-interaction
```

16. Alterar o arquivo AbstractOracleDriver que fica no diretório
    \Doctrine\DBAL\Driver:
```php
    // De:
    protected function getEasyConnectString(array $params)
    {
        return (string) EasyConnectString::fromConnectionParameters($params);
    }
    
    // Para:
    protected function getEasyConnectString(array $params)
    {
        return '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=oracledb)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=ORCLCDB.localdomain)))';
    }
```

17. Subir o container php-dev:
```bash
# subir o ambiente na versao atualizada 
# ATENÇÃO: COMENTAR RECRIAÇÃO DO BANCO
$ rm -rf var/cache
$ sudo docker-compose up php-dev
```


18. Rodar dentro do container php-dev os comandos de migration para extrair
    a diferença entre as versões **para o banco Oracle**.
```bash
$ sudo docker exec -it php-dev bash
$ bin/console doctrine:migrations:diff                                                                                                                        
 [WARNING] You have 38 available migrations to execute.                                                                 
 Are you sure you wish to continue? (yes/no) [yes]:
 > yes
 Generated new migration class to "/app/src/Migrations/Version20240223223521.php"
 To run just this migration for testing purposes, you can use migrations:execute --up 'DoctrineMigrations\\Version20240223223521'
 To revert the migration you can use migrations:execute --down 'DoctrineMigrations\\Version20240223223521'

```

19. Revisar e editar o arquivo passando o seu conteúdo para o arquivo
    de migrations anterior (todo o código em único arquivo).

```php
   public function up(Schema $schema): void
    {
        // incluir este desvio condicional
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // ORACLE
            // Código oriundo do arquivo Version20240223223521 após editado e revisado
        } else {
            // MYSQL
            // Código colocado  no passo 13            
        }
    }
    
    public function down(Schema $schema): void
    {
        // incluir este desvio condicional
        if ('oracle' === $this->connection->getDatabasePlatform()->getName() ||
            'postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            // Código oriundo do arquivo Version20240223223521 após editado e revisado
        } else {
            // Código colocado  no passo 13            
        }
    }
```

20. Apagar o segundo arquivo gerado, pois haverá apenas um arquivo
    de migration. Fazer o rollback nas alterações feitas nos arquivos .env
    e docker/dev/php/docker-entrypoint.sh. Comitar e subir esta versão.
    Fazer a tag e passar para a develop.

```bash
$ rm src/Migrations/Version20240223223521.php

# rollback versao original
$ git checkout -- .env    

# rollback versao original
$ git checkout -- docker/dev/php/docker-entrypoint.sh   

# versao definitiva arquivo migrations 
$ git commit -m "versao definitiva arquivo migrations" -a
$ git push

# tag de versao 
$ git tag 1.15.0  
$ git push origin 1.15.0

# repassando tudo para develop
$ git checkout develop
$ git merge release/1.15.0
$ git push  
```
