# RSS Reader Backend

## Requirements

- php >= 7.3
- ext-ctype
- ext-zip
- ext-iconv

## Run using Docker (recommended)

Run in project directory

`$ docker-copose build` to build image

`$ docker-compose up` to run image

When API will be available on `http://localhost:8000/`

## Run manullay

Create `.env.local` file in project directory. Example could be found as `.env` file or `.env.local` file in `.docker/php-fpm/.env.local`. Then run in project directory:

`$ composer install`

`$ php bin/console doctine:database:create`

`$ php bin/console doctrine:migration:migrate`

`$ php -S 127.0.0.1:8000 -t public`

## Run tests

`$ php bin/console doctine:database:create -e test`

`$ php bin/console doctine:migration:migrate -e test`

`$ php bin/phpunit`