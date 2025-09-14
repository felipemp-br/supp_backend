#!/bin/bash

php /var/www/html/bin/console doctrine:database:create --no-interaction --env=prod
php /var/www/html/bin/console doctrine:schema:update --force --no-interaction --env=prod
php /var/www/html/bin/console doctrine:fixtures:load --group prod --no-interaction --env=prod

php /var/www/html/bin/console ongr:es:index:create --index=pessoa --if-not-exists --no-interaction --env=prod
php /var/www/html/bin/console ongr:es:index:create --index=modelo --if-not-exists --no-interaction --env=prod
php /var/www/html/bin/console ongr:es:index:create --index=repositorio --if-not-exists --no-interaction --env=prod
php /var/www/html/bin/console ongr:es:index:create --index=processo --if-not-exists --no-interaction --env=prod

php /var/www/html/bin/console ongr:es:template:create --index=componente_digital --no-interaction --env=prod

php /var/www/html/bin/console ongr:es:pipeline:create --index=componente_digital --no-interaction --env=prod

#Armazenar variavel do banco Postgres serviço - Banco Homologação:
pessoa_endId=$(psql -U root -h 191.232.244.118 -p 5432 -d supp -t -c "SELECT max(id) FROM ad_pessoa;")
modelo_endId=$(psql -U root -h 191.232.244.118 -p 5432 -d supp -t -c "SELECT max(id) FROM ad_modelo;")
componente_digital_endId=$(psql -U root -h 191.232.244.118 -p 5432 -d supp -t -c "SELECT max(id) FROM ad_componente_digital;")
processo_endId=$(psql -U root -h 191.232.244.118 -p 5432 -d supp -t -c "SELECT max(id) FROM ad_processo;")
comunicacao_judicial_endId=$(psql -U root -h 191.232.244.118 -p 5432 -d supp -t -c "SELECT max(id) FROM jd_com_judicial;")
tese_endId=$(psql -U root -h 191.232.244.118 -p 5432 -d supp -t -c "SELECT max(id) FROM ad_tese;")



#Verificar se retornou variavel ou null (se retornar null não executar index populate da variavel)
echo $pessoa_endId
echo $modelo_endId
echo $componente_digital_endId
echo $processo_endId
echo $comunicacao_judicial_endId
echo $tese_endId

#Indexação e populate no Banco
php bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulatePessoaMessage" --startId=1 --endId="$pessoa_endId"  --batch=5 --no-interaction --env=prod
php bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateModeloMessage" --startId=1 --endId="$modelo_endId"  --batch=5 --no-interaction --env=prod
php bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateComponenteDigitalMessage" --startId=1 --endId="$componente_digital_endId"  --batch=5 --no-interaction --env=prod
php bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateProcessoMessage" --startId=1 --endId="$processo_endId" --batch=5 --no-interaction --env=prod --no-debug
php bin/console ongr:es:index:populate --message="SuppCore\JudicialBackend\Command\Elastic\Messages\PopulateComunicacaoJudicialMessage" --startId=1 --endId="$comunicacao_judicial_endId" --batch=5 --no-interaction --env=prod
php bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateTeseMessage" --startId=1 --endId="$tese_endId" --batch=5 --no-interaction --env=prod --no-debug
