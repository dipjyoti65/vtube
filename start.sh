#!/bin/bash

# Create SQLite DB file if not exists
touch /var/www/html/database/database.sqlite

# Run Laravel migrations
php artisan migrate --force

# Start Apache
apache2-foreground
