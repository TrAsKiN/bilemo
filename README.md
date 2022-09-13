# BileMo [![SymfonyInsight](https://insight.symfony.com/projects/b2fde0bb-e127-454f-894a-ae059e3ef385/mini.svg)](https://insight.symfony.com/projects/b2fde0bb-e127-454f-894a-ae059e3ef385)

## Prerequisites

* Docker
* PHP 8.1
* Symfony CLI

## Installation and configuration

1. Clone or download the repository
2. Duplicate and rename the `.env` file to `.env.local` and modify the necessary information (`APP_ENV`, `APP_SECRET`, ...)
3. Install the dependencies with `symfony composer install --optimize-autoloader`
4. Run migrations with `symfony console doctrine:migrations:migrate --no-interaction`
5. Add default datasets with `symfony console doctrine:fixtures:load --no-interaction`

## Launch the local server

Run the command `symfony server:start -d` to start the local server and access the site at the indicated address or type `symfony open:local`.

The documentation is available at `/api/doc`.

## Default account credentials

- First account
    * Username: `moi@traskin.net`
    * Password: `test`
- Second account
    * Username: `test@example.com`
    * Password: `example`
