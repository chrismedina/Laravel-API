# Laravel-API

To start working on testing API endpoints do:

1. Download ZIP or git clone this repo

2. composer update

3. php artisan migrate

4. php artisan passport:install

5. php artisan db:seed

To test using Laravel's server and have access to API endpoint URLs:

6. php artisan serve




Use the following users to test admin vs. regular user without admin role / permissions.

admin :

josh@gmail.com : password

regular :

basic@gmail.com : password



Example API endpoint to test in Insomnia/Postman:

POST -> http://127.0.0.1:8000/api/login

Header:
Content-Type application/json

JSON :
{
    "email" : "josh@gmail.com",
    "password" : "password"
}

You'll get a bearer token if all things are configured properly. Similarly use the example non-admin user/password for
a different token to test endpoints with.

7. php artisan route:list

## List of all API end points with the above command