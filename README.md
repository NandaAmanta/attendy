# ATTENDY Application
## Tech Stack

-   Laravel 11 + Filament 3
-   PHP >= 8.2
-   Composer
-   NodeJS
-   Mysql

## Installation
1. Clone the repository by using this command : 
    ```
    git clone https://github.com/NandaAmanta/attendy.git
    ```

2. Move to the project root directory, for example : 
    ```
    cd project_dir_name/
    ```

3. Create a copy of `.env.example` and name it `.env` and set it up

4. Install dependencies by using this command : 
    ```
    composer install
    npm i
    ```

5. Generate the key by using this command :
    ```
    php artisan key:generate

    ```
6. Run the migrations by using this command :
    ```
    php artisan migrate
    ```

7. you can run this command to seed the permissions data:
    ```
    php artisan db:seed
    ```
    
    but if you want to seed owner data :
    ```
    php artisan db:seed OwnerSeeder
    ```

8. Generate storage link, you can run this command : 
 ```
 php artisan storage:link
 ```

 9. Run the application you can follow this several instruction:\
    9.1. Compile the frontend assets:
    ```
    npm run build
    ```

    9.2. run the aplication : 
    ```
    php artisan serve
    ```

## Developement note
- if you run the `OwnerSeeder`, you will get these several users :

```
name : owner
Email : owner@email.com
Role : OWNER
Password : password
```



