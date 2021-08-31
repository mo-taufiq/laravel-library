## Getting Started
- Clone repository.
    ```bash
    git clone git@github.com:mo-taufiq/laravel-library.git
    ```
- Run Composer Install.
    ```bash
    composer install
    ```
- Copy file `.env.example` then rename it to `.env`.
- Run this command.
    ```
    php artisan key:generate
    ```
- Create new database using `MySQL`, the name of new database is up to you.
- Set database configuration on file `.env`, based on your local database configuration.
    ```
    DB_DATABASE=<the name of new database that already you created>
    DB_USERNAME=<your local mysql username>
    DB_PASSWORD=<your local mysql password>
    ```
- Run this command
    ```bash
    php artisan migrate:fresh --seed
    php artisan serve
    ```
    
## Predefined User
### As admin user (Admin)
    username: admin
    password: admin

### As client user (Non Admin)
    username: client
    password: client