How to use this:

1 - Set in Composer.json: "autoload": { "classmap": [ "database", "database/migrations/kNet_DB", ], for a proper rollback use.

2 - Migrate: php artisan migrate --database=kNet_it --path=./database/migrations/kNet_DB

3 - Create a new migration: php artisan make:migration ****** --create=** --path=./database/migrations/kNet_DB

##ROLLBACK

4 - To rollback php artisan migrate:rollback --step=1

##MODEL

5 - php artisan make:model ArcaModels/***** -table=****

##CONTROLLER

6 - php artisan make:controller ....