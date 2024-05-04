FROM cloudflare/cloudflared:2024.4.0 as cloudflared
FROM php:8.0.30-fpm-alpine AS system-setup

RUN apk add --no-cache dcron busybox-suid libcap curl zip unzip git

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions intl bcmath gd pdo_mysql pdo_pgsql opcache redis uuid exif pcntl zip

# Install supervisord implementation
COPY --from=ochinchina/supervisord:latest /usr/local/bin/supervisord /usr/local/bin/supervisord

# Install caddy
COPY --from=caddy:2.2.1 /usr/bin/caddy /usr/local/bin/caddy
RUN setcap 'cap_net_bind_service=+ep' /usr/local/bin/caddy

# Install ffmpeg
COPY --from=mwader/static-ffmpeg:4.3.1-2 /ffmpeg /usr/local/bin/
COPY --from=mwader/static-ffmpeg:4.3.1-2 /ffprobe /usr/local/bin/

# Install composer
COPY --from=composer/composer:2 /usr/bin/composer /usr/local/bin/composer
ARG COMPOSER_AUTH
ENV COMPOSER_AUTH=${COMPOSER_AUTH}

# Install cloudflared
COPY --from=cloudflared /etc/ssl/certs/ca-certificates.crt /etc/ssl/certs/ca-certificates.crt
COPY --from=cloudflared /usr/local/bin/cloudflared /usr/local/bin

FROM system-setup AS app-setup

# Set working directory
ENV LARAVEL_PATH=/srv/app
WORKDIR $LARAVEL_PATH

# Add non-root user: 'app'
ARG NON_ROOT_GROUP=${NON_ROOT_GROUP:-app}
ARG NON_ROOT_USER=${NON_ROOT_USER:-app}
RUN addgroup -S $NON_ROOT_GROUP && adduser -S $NON_ROOT_USER -G $NON_ROOT_GROUP
RUN addgroup $NON_ROOT_USER wheel

# Set cron job
# COPY ./.deploy/config/crontab /etc/crontabs/$NON_ROOT_USER
# RUN chmod 777 /usr/sbin/crond
# RUN chown -R $NON_ROOT_USER:$NON_ROOT_GROUP /etc/crontabs/$NON_ROOT_USER && setcap cap_setgid=ep /usr/sbin/crond

# Switch to non-root 'app' user & install app dependencies
# COPY composer.json composer.lock ./
# RUN chown -R $NON_ROOT_USER:$NON_ROOT_GROUP $LARAVEL_PATH
USER $NON_ROOT_USER
# RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader
# RUN rm -rf /home/$NON_ROOT_USER/.composer

# Copy app
# COPY --chown=$NON_ROOT_USER:$NON_ROOT_GROUP . $LARAVEL_PATH/
COPY ./.deploy/config/php/local.ini /usr/local/etc/php/conf.d/local.ini
# Copy cloudflared config
COPY ./.deploy/localhost/cloudflared/config.yml /home/app/.cloudflared/config.yml

# Start app
EXPOSE 80
ENTRYPOINT ["sh", "/srv/app/.deploy/localhost/entrypoint.sh"]
