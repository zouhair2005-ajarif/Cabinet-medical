FROM php:8.2-cli

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /app

# Copier projet
COPY . .

# Installer dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Générer clé Laravel
RUN php artisan key:generate

# Exposer port
EXPOSE 10000

# Lancer serveur
CMD php artisan serve --host=0.0.0.0 --port=10000