FROM php:7.4-apache

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    curl

# Enable required PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Copy existing application directory contents
COPY . /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set up Laravel project
RUN composer install --no-dev --optimize-autoloader

# Set file permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Generate key
RUN php artisan key:generate

# Set Apache document root
RUN sed -ri -e 's!/var/www/html/public!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html/public!/var/www/html/public!g' /etc/apache2/apache2.conf

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Clear caches
RUN php artisan cache:clear
RUN php artisan config:clear

# Expose port 8000 and start Apache
EXPOSE 8000

CMD ["apache2-foreground"]
