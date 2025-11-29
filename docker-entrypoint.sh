#!/bin/sh
set -e

# Run migrations (force for production, no fresh/seed)
echo "Running migrations..."
php artisan migrate --force

# Clear/Cache config
echo "Caching configuration..."
php artisan optimize

# Execute the main command
echo "Starting server..."
exec "$@"
