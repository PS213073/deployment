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

# Copy existing application directory contents
COPY --chown=www-data:www-data . /var/www
RUN ls -la /var/www

# Specifically ensure public directory is copied
COPY --chown=www-data:www-data ./public /var/www/public
RUN ls -la /var/www/public
# Install project dependencies
RUN composer install

# Run database migrations
RUN php artisan migrate --force

# Change owner and permissions of the storage
# RUN chown -R www-data:www-data /var/www/storage
# RUN chmod -R 755 /var/www/storage
# RUN chown -R www-data:www-data /var/www/bootstrap/cache
# RUN chmod -R 755 /var/www/bootstrap/cache

# # Update Apache configuration
# RUN a2enmod rewrite
# RUN sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf
# RUN mv /var/www/public /var/www/html

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Set ServerName to suppress Apache warning
# RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80
