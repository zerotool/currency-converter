#!/usr/bin/env bash

BASE_PATH=/var/www/currency-converter

docker-compose up -d

docker-compose exec -T php $BASE_PATH/bin/composer install -d $BASE_PATH
docker-compose exec -T php $BASE_PATH/bin/console doctrine:schema:create
docker-compose exec -T php $BASE_PATH/bin/console doctrine:migrations:migrate -n
docker-compose exec -T php $BASE_PATH/bin/console doctrine:fixtures:load -n
