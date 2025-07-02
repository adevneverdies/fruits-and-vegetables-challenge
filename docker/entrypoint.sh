#!/bin/sh

echo "Starting the Fruits and Vegetables Challenge application..."

# composer install

if [ -f /app/composer.json ]; then
    echo "Installing Composer dependencies..."
    if [ "$APP_ENV" = "prod" ]; then
        composer install --no-dev --optimize-autoloader
    else
        composer install
    fi
else
    echo "No composer.json found, skipping Composer install."
fi


exec "$@"