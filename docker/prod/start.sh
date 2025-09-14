#!/bin/bash
set -e

mkdir -p /var/www/html/var/cache
mkdir -p /var/www/html/var/log

setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx /var/www/html/var/cache /var/www/html/var/log
setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx /var/www/html/var/cache /var/www/html/var/log

php /var/www/html/bin/console assets:install --symlink --relative --no-interaction --env=prod
php /var/www/html/bin/console cache:clear --env=prod
php /var/www/html/bin/console cache:warmup --env=prod

if [ "$NGINX" == "true" ]
then
    service nginx start
    service php8.3-fpm start
fi

if [ "$WORKER" == "true" ]
then
    supervisord
fi
echo "Iniciando start.sh" >> /var/log/start.log