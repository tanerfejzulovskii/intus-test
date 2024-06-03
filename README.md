## Prerequisite

-   Node v18.12.1
-   npm v8.19.2
-   MySQL v8.4.0

## Technology Versions used

-   Vue.js ^3.4.27
-   Laravel ^10.10,

## Setup

-   Clone the repo
-   Run `composer install`
-   Run `npm install`
-   Copy .env.example. Rename .env.example to .env and update the parameters db connection and `GOOGLE_API_KEY` parameter with your API_KEY
-   Run `php artisan migrate`
-   Run `php artisan key:generate`
-   Run `npm run build`
-   Run `php artisan serve`

You can access the site on this url: http://127.0.0.1:8000/

To run the tests, execute this command: `vendor/bin/phpunit`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
