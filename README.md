## Getting Started
- Clone repository.
    ```bash
    git clone git@github.com:mo-taufiq/laravel-library.git
    ```
- Run Composer Install.
    ```bash
    composer install
    ```
- Copy file `.env.example` then rename it `.env`.
- Run this command.
    ```
    php artisan key:generate
    ```
- Create new database using `MySQL`.
- Set database configuration on file `.env`, based on your local database configuration.
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