FROM php:8.3-cli

# Install system dependencies and PHP extensions commonly used for CLI and testing
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
  git \
  unzip \
  zip \
  libzip-dev \
  libonig-dev \
  libxml2-dev \
  libicu-dev \
  zlib1g-dev \
  && docker-php-ext-install -j"$(nproc)" zip mbstring pcntl xml \
  && docker-php-ext-configure intl \
  && docker-php-ext-install -j"$(nproc)" intl \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && rm -rf /var/lib/apt/lists/*

# Install Composer (copy from the official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first so we can take advantage of Docker layer caching
COPY composer.json ./

# Install PHP dependencies (including dev packages for tests)
RUN composer install --no-interaction --prefer-dist --no-progress

# Copy project files
COPY . .

# Default to an interactive shell; common way to run tests is using phpdbg or vendor/bin/phpunit
CMD ["php", "-a"]
