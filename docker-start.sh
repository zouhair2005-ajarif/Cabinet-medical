#!/bin/bash
echo "🚀 Démarrage du Cabinet Médical..."

# Générer APP_KEY si vide
php artisan key:generate --force

# Optimiser
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrations
php artisan migrate --force
php artisan db:seed --force

# Démarrer Apache
echo "✅ Application prête !"
apache2-foreground