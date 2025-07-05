#!/bin/sh
set -e;

# Whilst this script is not doing anything the dockerfile can't,
# it is useful to run processes after docker-compose has mounted volumes
# otherwise our installed files at build time would be overwritten with any mounted volumes.

composer install && composer dump-autoload;

exec php-fpm;