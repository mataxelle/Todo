# Todo

## Table of contents

*  [General info](#general-info)
*  [Features](#features)
*  [Development environment](#development-environment)
*  [Install on local](#install-on-local)
*  [Install dependencies](#install-dependencies)

## General info

Project :  Improve an existing project => https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

## Features

### Front : users access

*  Register / login page
*  Homepage : Can found a create task button if connected user
*  Tasks list pages
*  Task page : Task with toggle button
*  User informations and password update form
*  Create / Edit figure task form

### Back : admin access

*  Admin / user pages : includes all CRUD actions

## Development environment

* PHP 8.1.10
* Symfony CLI
* Composer

## Requirements check

* symfony check:requirements

## Install on local

1. Clone the repo on your local webserver : [Repository](https://github.com/mataxelle/Todo.git).

2. Make sure you have Php and composer on your computer.

3. Create a .env.local file at the root of your project, same level as .env, and configure the appropriate values for your project to run.

```
#Database standard parameters

DATABASE_URL='your database'
```
4. Create a database run :

```
php bin/console doctrine:database:create
```
5. Generate database schema run :

```
php bin/console make:migration

php bin/console doctrine:migrations:migrate
```
6. Load fixtures run :

```
php bin/console doctrine:fixtures:load
```
7. Try to connect as an admin with : `admin@email.com` `azertyuiop`

### Start the environment

```
Composer install
symfony server:start
```
## Create fixtures for tests

1. Create a .env.test.local file at the root of your project, same level as .env and .env.test, and configure the appropriate values for your test to run.

```
#Database standard parameters

DATABASE_URL='your database'
```
2. Create a database  run :

```
php bin/console doctrine:database:create --env=test 
```
5. Generate database schema run :

```
php bin/console doctrine:migrations:migrate -n --env=test  
```
6. Load fixtures run :

```
php bin/console doctrine:fixtures:load --env=test 
```
## Tests

* php bin/phpunit --testdox
or
* php bin/phpunit --coverage-html var/log/test/test-coverage
