#!/bin/bash

php /var/www/html/bin/console doctrine:database:create --no-interaction --env=prod
php /var/www/html/bin/console doctrine:schema:update --force --no-interaction --env=prod
php /var/www/html/bin/console doctrine:fixtures:load --group prod --no-interaction --env=prod

php /var/www/html/bin/console ongr:es:index:create --index=pessoa --if-not-exists --no-interaction --env=prod
php /var/www/html/bin/console ongr:es:index:create --index=modelo --if-not-exists --no-interaction --env=prod
php /var/www/html/bin/console ongr:es:index:create --index=repositorio --if-not-exists --no-interaction --env=prod
php /var/www/html/bin/console ongr:es:index:create --index=processo --if-not-exists --no-interaction --env=prod
php /app/bin/console ongr:es:index:create --index=comunicacao_judicial --if-not-exists --no-interaction    

php /var/www/html/bin/console ongr:es:template:create --index=componente_digital --no-interaction --env=prod

php /var/www/html/bin/console ongr:es:pipeline:create --index=componente_digital --no-interaction --env=prod

php /var/www/html/bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateModeloMessage" --startId=1 --endId=10 --batch=5 --no-interaction --env=prod
php /app/bin/console ongr:es:index:populate --message="SuppCore\JudicialBackend\Command\Elastic\Messages\PopulateComunicacaoJudicialMessage" --startId=1 --endId=10 --batch=5 --no-interaction

