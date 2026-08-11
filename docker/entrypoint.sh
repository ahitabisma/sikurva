#!/bin/sh
set -e

# Pastikan struktur folder storage selalu ada,
# karena bind mount volume bisa menimpa hasil mkdir saat build
mkdir -p storage/app/private \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chmod -R 775 storage bootstrap/cache

exec "$@"