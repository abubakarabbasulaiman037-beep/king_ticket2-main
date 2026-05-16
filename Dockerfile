FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    sqlite3 \
    libsqlite3-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_sqlite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

RUN php -m

RUN npm install && npm run build

RUN composer install --ignore-platform-req=ext-gd --no-dev --optimize-autoloader

RUN touch database/database.sqlite
RUN php artisan migrate --force

RUN php artisan storage:link || true
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true

EXPOSE 10000

ENV SCRIPT_NAME=/
ENV SESSION_DRIVER=file
ENV SESSION_LIFETIME=120
ENV SESSION_SECURE_COOKIE=true
ENV SESSION_SAME_SITE=lax
ENV CACHE_STORE=file
ENV QUEUE_CONNECTION=sync

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
