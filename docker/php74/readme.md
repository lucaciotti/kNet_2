docker-compose exec app ls -l

# Composer Install
docker-compose exec app rm -rf vendor composer.lock
docker-compose exec app composer install

# Artisan operations

docker-compose exec app php artisan key:generate


# Logs
docker-compose logs nginx
