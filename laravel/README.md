
# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/9.x/installation)


Clone the repository

    git clone git@github.com:ljubisaivanovic/test.git
    
Switch to the repo folder

    cd test/laravel    

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env
    
Generate a new application key

    php artisan key:generate

Run the database migrations with seed(**Set the database connection in .env before migrating**)

    php artisan migrate --seed

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

# Known issues

- If the client doesn't set the `Accept` header to `application/json` while trying to create new posts/comments, the application's validator will try to redirect to the same page to display errors instead of returning them as JSON.
