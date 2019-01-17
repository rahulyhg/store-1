#! /bin/sh

echo "Start getting mapping and entities"

php bin/console doctrine:mapping:import App\\Entity yaml --path=config/doctrine
php bin/console doctrine:mapping:import App\\Entity annotation --path=src/Entity
php bin/console make:entity --regenerate App

echo "============== End ============="
