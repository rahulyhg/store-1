#! /bin/sh

echo "Start getting mapping and entities"

rm -rf config/doctrine/*.yaml
php bin/console doctrine:mapping:import App\\Entity yaml --path=config/doctrine

rm -rf src/Entity/*.php
php bin/console doctrine:mapping:import App\\Entity annotation --path=src/Entity
php bin/console make:entity --regenerate App

echo "============== End ============="
