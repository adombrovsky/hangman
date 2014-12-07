hangman
=======

This is a minimal version of Hangman game developed by using Laravel + Backbone.js

How to setup
============
This application require php >= 5.4, apache2 mod_rewrite

1. git clone https://github.com/farwless/hangman.git hangman
2. cd hangman
3. make folder app/storage writable
4. create database hangman and change credentials to this database in app/config/database.php
5. php artisan dump-autoload
6. php artisan migrate
7. php artisan serve --host=localhost --port=8000
8. open in browser url: http://localhost:8000