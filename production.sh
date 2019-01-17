#! /bin/sh

echo "Production operations..."
git pull origin master
sleep 3
echo "Cache clear and warmup for prod env"
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug
