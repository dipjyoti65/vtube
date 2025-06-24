#!/bin/bash

# Ensure directory is writable
mkdir -p /var/www/html/database
chown -R www-data:www-data /var/www/html/database
chmod -R 775 /var/www/html/database

# Create SQLite DB file at runtime (if it doesn't exist)
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
    chmod 664 /var/www/html/database/database.sqlite
fi

# Run migrations
php artisan migrate --force

# Start Apache
apache2-foreground






# #!/bin/bash

# # Create SQLite DB file if not exists
# touch /var/www/html/database/database.sqlite

# # Run Laravel migrations
# php artisan migrate --force

# # Start Apache
# apache2-foreground
