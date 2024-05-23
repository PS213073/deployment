# Start from the PHP 8.2 Apache image
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Install project dependencies
RUN composer install

# Run database migrations
RUN php artisan migrate --force

# Change owner and permissions of the storage
RUN chown -R www-data:www-data /var/www/storage
RUN chmod -R 755 /var/www/storage

# Set ServerName to suppress Apache warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# NodeJS
RUN curl -o- https://deb.nodesource.com/setup_16.x | bash

# Dependencies
RUN apt-get update && apt-get install -y git nginx xz-utils nodejs

# S6 Overlay
ARG OVERLAY_VERSION="v3.1.1.2"
ARG OVERLAY_ARCH="x86_64"

ENV S6_KEEP_ENV=1
ENV S6_CMD_WAIT_FOR_SERVICES_MAXTIME=30000

ADD https://github.com/just-containers/s6-overlay/releases/download/${OVERLAY_VERSION}/s6-overlay-noarch.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-noarch.tar.xz
ADD https://github.com/just-containers/s6-overlay/releases/download/${OVERLAY_VERSION}/s6-overlay-${OVERLAY_ARCH}.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-${OVERLAY_ARCH}.tar.xz

# PHP Extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions %EXTENSIONS%

# Copy files
COPY files/root/ /

EXPOSE 80
ENTRYPOINT [ "/init" ]
