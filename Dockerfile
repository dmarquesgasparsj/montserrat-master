FROM node:22-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY webpack.mix.js ./
COPY resources/assets resources/assets
RUN npm run production

FROM php:8.3-apache-bookworm
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && docker-php-ext-install bcmath pdo_mysql \
    && a2enmod rewrite \
    && sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/polanco.conf \
    && a2enconf polanco \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist
COPY . .
COPY --from=assets /app/public/ public/
RUN mkdir -p storage/app/private storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && composer dump-autoload --no-dev --no-scripts --optimize \
    && chown -R www-data:www-data storage bootstrap/cache
EXPOSE 80
