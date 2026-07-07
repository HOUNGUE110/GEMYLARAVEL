FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

# Activation du module de réécriture Apache
RUN a2enmod rewrite

# Configuration du dossier public de Laravel comme racine du serveur
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Copie du code du projet
COPY . /var/www/html

# Configuration des permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN php artisan migrate --force

EXPOSE 80