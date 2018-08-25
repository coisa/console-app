FROM php:cli

# MySQL dependecies
RUN docker-php-ext-install pdo pdo_mysql

COPY ./ /app

# Install Composer
COPY --from=composer:1.6 /usr/bin/composer /usr/bin/composer

# Project dependecies
RUN composer install \
        --no-dev \
        --prefer-dist \
        --optimize-autoloader \
    ; \
    composer clearcache

WORKDIR /app

CMD /app/bin/console
