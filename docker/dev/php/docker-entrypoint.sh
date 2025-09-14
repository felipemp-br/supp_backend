#!/bin/bash
set -e

# No OSX, é necessário ajustar na GUI Docker os recursos de disco para 32GB (preferences/resources/advanced)

# comando de voltar para a raiz necessário para simplificar o docker-compose.yaml
echo "Voltando para raiz do projeto para iniciar a execução do script docker-entrypoint.sh"
cd /app

echo "docker-entrypoint.sh: Aguardando por 15s as dependências subirem"
sleep 15

service memcached start

phpdismod xdebug

echo "info mkdir: durante a primeira execução, pastas var e filesystem serão criadas no dir do projeto. Caso trave, ajustar grupo/permissoes e executar novamente: sudo docker-compose up php-dev"
rm -rf /app/var/cache
rm -rf /app/var/log

mkdir -p /app/var/cache
mkdir -p /app/var/log
mkdir -p /app/filesystem

# Certifique-se de criar o diretório para as chaves JWT
mkdir -p /config/jwt

# Gerar as chaves JWT (garanta que o caminho esteja correto)
make generate-jwt-keys

# Ajuste de permissões após a geração das chaves
chmod -R 777 /config/jwt
chown -R www-data:www-data /config/jwt

# Ajustar permissões para o arquivo específico
chmod 644 /config/jwt/private.pem
chmod 644 /config/jwt/public.pem

rm -rf composer.lock
mkdir -p ~/.ssh

git config --global --add safe.directory /app

echo "info known_hosts: Caso trave, comentar a linha e executar novamente: sudo docker-compose up php-dev"
#ssh-keyscan github.com >> ~/.ssh/known_hosts

echo "info composer: Caso trave, ajustar grupo/permissoes na pasta do projeto"
composer -V
composer install --ansi --no-interaction

# Step 8
echo "Dando permissões recursivo na pasta do projeto..."
export PATH="/app/vendor/bin:$PATH"

chmod -R o+s+w /app

# echo "Criando database com doctrine..."
# php /app/bin/console doctrine:database:drop --connection default --force --no-interaction
# php /app/bin/console doctrine:database:create --connection default --no-interaction
# php /app/bin/console doctrine:schema:update --em default  --force --no-interaction --complete
# php /app/bin/console doctrine:fixtures:load --em default --append --group dev --no-interaction

# php /app/bin/console ongr:es:index:create --index=pessoa --if-not-exists --no-interaction -vvv
# php /app/bin/console ongr:es:index:create --index=modelo --if-not-exists --no-interaction
# php /app/bin/console ongr:es:index:create --index=repositorio --if-not-exists --no-interaction
# php /app/bin/console ongr:es:index:create --index=processo --if-not-exists --no-interaction
# php /app/bin/console ongr:es:index:create --index=tese --if-not-exists --no-interaction

# php /app/bin/console ongr:es:template:create --index=componente_digital --if-not-exists --no-interaction
# php /app/bin/console ongr:es:pipeline:create --index=componente_digital --no-interaction

# php /app/bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulatePessoaMessage" --startId=1 --endId=30 --batch=5 --no-interaction
# php /app/bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateModeloMessage" --startId=1 --endId=30 --batch=5 --no-interaction
# php /app/bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateProcessoMessage" --startId=1 --endId=30 --batch=5 --no-interaction
# php /app/bin/console ongr:es:index:populate --message="SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateTeseMessage" --startId=1 --endId=30 --batch=5 --no-interaction

# php /app/bin/console ongr:es:index:create --index=comunicacao_judicial --if-not-exists --no-interaction
# php /app/bin/console ongr:es:index:populate --message="SuppCore\JudicialBackend\Command\Elastic\Messages\PopulateComunicacaoJudicialMessage" --startId=1 --endId=10 --batch=5 --no-interaction

# # Indice utilizado para armazenar dados de modelos a serem usado via RAG
# php /app/bin/console opensearch:densevector:create

crontab /etc/crontab.txt

cron

date
#
echo "info xdebug: vide config em docker-compose.yml"

if [ -z "$DISABLE_XDEBUG" ]; then
    phpenmod xdebug
else
    echo "Xdebug is disabled by environment variable DISABLE_XDEBUG"
fi

service supervisor start

# Step 10
php -v
if [ -z "$USE_PHP_FPM_AND_CADDY" ]; then
    echo "Inicializing application using PHP Server"
    php -S 0.0.0.0:8000 -t public/
else
    if [ ! -d "/ssl" ]; then
        echo "Generating SSL certificate"
        mkdir -p /ssl
        openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout /ssl/localhost.key -out /ssl/localhost.crt -subj "/CN=localhost"
    fi
    echo "Inicializing application using PHP FPM and Caddy"
    /etc/init.d/php8.3-fpm start
    mkdir -p /root/.local/share/caddy
    caddy run --config=/app/docker/dev/php/Caddyfile
fi
exec "$@"
