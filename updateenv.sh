#!/usr/bin/env bash

env_option="dev"
with_fixtures=0

while getopts ":e:f" opt; do
  case $opt in
    e)
      env_option=$OPTARG
      ;;
    f)
      with_fixtures=1
      ;;
  esac
done

rm -rf var/cache/${env_option}/*
rm -rf web/css/*
rm -rf web/js/*

composer install

php bin/console doctrine:migrations:migrate --env=$env_option --no-interaction

if [[ ${with_fixtures} == 1 ]]; then
    php bin/console doctrine:fixtures:load --env=$env_option --no-interaction
fi

php bin/console cache:warmup --env=$env_option

bower install

php bin/console assetic:dump --env=$env_option --no-debug
php bin/console assetic:dump --env=$env_option

chown -R www-data:www-data var src vendor web app bin
