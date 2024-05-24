# Laravel Auth

Laravel Project using Passport and Socialite for Authentication.

### To run the project

1. `composer install` to install related package
2. `docker compose up` to run the application and database

### Command that need to be run for the first time

1. copy `.env.example` to `.env` and do adjustment if needed
2. run `php artisan key:generate` to generate or rorate application key
3. run `php artisan migrate` or `php artisan migrate:fresh` to reset database
4. run `php artisan passport:keys --force` to generate oauth2 keys
5. run `php artisan passport:client --password --provider {model}` to support oauth2 client on `Model`
