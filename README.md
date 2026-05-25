composer install
npm run build

php artisan migrate:fresh --seed
php artisan shield:generate --all
