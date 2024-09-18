# run migratio
symfony server:start
php bin/console make:migration
php bin/console doctrine:migrations:migrate