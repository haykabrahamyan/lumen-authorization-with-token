# lumen-authorization-with-token
Simple API to authentication via Lumen

# run
clone the repository and run following commands on the root directory and add your database access to .env file
```
composer install
cp .env.example .env
php artisan migrate
php -S localhost:8000 -t public
```
after start you will run server on 8000 port, which can be accessed via http://localhost:8000


# possible functions

#### /api/register

|name |url| parameters | type | |  
| --- | --- | --- | --- | --- |
| register user |/api/register| `name`, `email`, `password` | POST | required all parameters  |
|login user |/api/login| `email`, `password`| POST | required all parameters |
|get user profile |/api/profile | `Bearer Atuhorization token`| GET || 

