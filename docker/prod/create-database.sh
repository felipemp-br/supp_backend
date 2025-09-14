#!/bin/bash

#criação de database separada para poder ser chamado no ambiente de integração e local, se for necessário
echo "Criando database com doctrine..."

envApp=${APP_ENVIRONMENT:homol}
if [[ "$envApp" == "int" || "$envApp" == "local" || "$envApp" == "jenkins"  ]];
then
    #não faz drop!!!
    #php /var/www/html/bin/console doctrine:database:drop --force --no-interaction
    php /var/www/html/bin/console doctrine:database:create --no-interaction
    php /var/www/html/bin/console doctrine:schema:update --force --no-interaction
else
    echo "ERROR: criação de banco de dados foi ignorada, pois este script só pode ser executado em ambiente local, int ou jenkins."
    exit 1 # terminate and indicate error
fi