::::::::::RUN PROJECT:::::::::::

First Clone the Git Repository: git clone

Install Composer Dependencies: composer install

Create a .env File: .env.example .env

Generate an Application Key: php artisan key:generate

Set the database name : banking_management_system  Migrate the Database: php artisan migrate

Note: Admin user can't create in my system. Admin will create manually or import my given database in git.

4 user and use 3 guard and 3 url given below:
For user     : http://localhost/banking-management-system/
For admin    : http://localhost/banking-management-system/admin
For affiliate: http://localhost/banking-management-system/affiliate
