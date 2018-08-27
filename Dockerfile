FROM php:cli-alpine

# MySQL dependecies
RUN docker-php-ext-install pdo pdo_mysql

COPY ./ /app
WORKDIR /app

# Install Composer
COPY --from=composer:1.6 /usr/bin/composer /usr/bin/composer

# Project dependecies
RUN composer install \
        --no-dev \
        --prefer-dist \
        --optimize-autoloader \
    ; \
    composer clearcache

RUN crontab /app/config/crontab.txt

CMD /usr/sbin/crond -f -S
