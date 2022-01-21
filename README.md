Setup a DB and .env
```shell
replace .env.example with your database credentials
In the root of you project run:
```shell
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve