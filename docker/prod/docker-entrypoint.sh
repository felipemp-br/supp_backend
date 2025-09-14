#!/bin/bash
set -e

service memcached start

crontab /etc/crontab.txt

cron

tail -f /dev/null

exec "$@"