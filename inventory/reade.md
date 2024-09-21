# run migratio
symfony server:start
php -S 127.0.0.1:8000 -t public
php bin/console make:migration
php bin/console doctrine:migrations:migrate