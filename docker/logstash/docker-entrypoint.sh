#!/bin/bash
set -e

#/usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
/usr/share/logstash/bin/logstash -f /etc/logstash/conf.d/indexer.conf

exec "$@"
