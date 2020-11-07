#!/bin/sh

echo "🎬 entrypoint.sh"


echo "🎬 artisan commands"

# e.g. php artisan app:on-deploy
# php artisan cache:clear
php artisan migrate --no-interaction --force

echo "🎬 start supervisord"

supervisord -c $LARAVEL_PATH/.deploy/config/supervisor.conf
