Setup Steps

replace .env.example with your database credentials

In the root of you project run:

$ composer install
$ php artisan key:generate
$ php artisan migrate
$ php artisan db:seed
$ php artisan serve
