# Racebets

## Installation
Run this scripts to setup the server
- `composer install`
- `cp .env.example .env`

the next command create the tables in the DB, setup correctly the database environment in the `.env` file before:
- `php artisan migrate`

## Usage

### Setting env variables

The following variables can be set in the .env file to connect to the database (modify the variables where necessary to connect to your local db):
- `DB_CONNECTION` (default: mysql)
- `DB_HOST` (default: 127.0.0.1)
- `DB_PORT` (default: 3306)
- `DB_DATABASE` (default: racebets)
- `DB_USERNAME` (default: username)
- `DB_PASSWORD` (default: secret)

### Postman Collection
this file is a Postman collection with examples of API request
`Racebets.postman_collection.json`


### Start the server
launch this script to start up the server locally
-  `php -S localhost:8000 -t public`
 