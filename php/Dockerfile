FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    libgd-dev \
    libpng-dev \
    libmagickwand-dev \
    unzip \
    zip \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    intl \
    pdo_mysql \
    opcache \
    zip \
    gd \
    exif

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Imagick extension
RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Install Swoole extension
RUN pecl install swoole \
    && docker-php-ext-enable swoole

# Clear cache
RUN apt-get clean \
    && rm -rf /tmp/* /var/tmp/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www
