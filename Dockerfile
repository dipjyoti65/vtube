# Base
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip gd

# Enable Apache rewrite
RUN a2enmod rewrite

# Workdir
WORKDIR /var/www/html

# Copy project
COPY . .

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ✅ Create SQLite DB file
RUN touch /var/www/html/database/database.sqlite

# Point Apache to /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Port
EXPOSE 80





# # Base image
# FROM php:8.2-apache

# # Install system dependencies
# RUN apt-get update && apt-get install -y \
#     git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
#     && docker-php-ext-install pdo pdo_mysql zip gd

# # Enable Apache rewrite module
# RUN a2enmod rewrite

# # Set working directory
# WORKDIR /var/www/html

# # Copy Laravel app files
# COPY . .

# # Install Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Install PHP dependencies
# RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# # Set correct permissions
# RUN chown -R www-data:www-data /var/www/html \
#     && chmod -R 755 /var/www/html/storage

# # Change Apache document root to Laravel's public folder
# RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# # Expose port 80
# EXPOSE 80



