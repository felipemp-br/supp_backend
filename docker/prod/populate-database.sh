#!/bin/bash

echo "Populando database com doctrine..."

envApp=${APP_ENVIRONMENT:homol}
if [[ "$envApp" == "int" || "$envApp" == "local" || "$envApp" == "jenkins"  ]];
then

    #Criando o diretório de documentos no filesystem
    mkdir -p /filesystem/documentos

    php /var/www/html/bin/console doctrine:fixtures:load --group dev --no-interaction

    #Popular indices
    exec /var/www/html/docker/prod/load-env.sh
else
    echo "ERROR: criação de banco de dados foi ignorada, pois este script só pode ser executado em ambiente local, int ou jenkins."
    exit 1 # terminate and indicate error
fi