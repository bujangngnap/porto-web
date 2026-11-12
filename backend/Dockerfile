FROM php:8.3-cli-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libsqlite3-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j"$(nproc)" pdo_sqlite mbstring xml zip gd bcmath \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install PHP dependencies cleanly without dev packages or scripts yet
COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --no-interaction

# Copy Laravel backend and root /public assets (resolved by /portfolio-media/{path})
COPY backend/ /app/
COPY public/ /public/

RUN composer dump-autoload --optimize --no-dev \
  && php -r "file_exists('.env') || copy('.env.example', '.env');" \
  && printf "upload_max_filesize=20M\npost_max_size=25M\nmemory_limit=256M\n" > /usr/local/etc/php/conf.d/uploads.ini \
  && sed -i 's/\r$//' /app/docker-entrypoint.sh \
  && chmod +x /app/docker-entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/app/docker-entrypoint.sh"]
