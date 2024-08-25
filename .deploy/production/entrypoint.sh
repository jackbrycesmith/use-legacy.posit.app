#!/bin/sh
echo "🎬 entrypoint.sh: [$(whoami)] [PHP $(php -r 'echo phpversion();')] [$(pwd)]"

composer dump-autoload --no-interaction --no-dev --optimize

echo "🎬 artisan commands"

php artisan optimize
php artisan migrate --no-interaction --force

echo "🎬 start supervisord"
supervisord -c /srv/app/.deploy/production/supervisor.conf
