# Todo

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

## Table of contents

*  [General info](#general-info)
*  [Features](#features)
*  [Development environment](#development-environment)
*  [Install on local](#install-on-local)
*  [Install dependencies](#install-dependencies)

## General info

Project : Todo.

## Features

### Front : users access

*  Homepage : Can found a create task button if connected user.
*  Task pages
*  Register / login page
*  Change user informations and password
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
4. Create a database and for fixtures run :

```
symfony console doctrine:fixtures:load
```
5. Try to connect as an admin with : `admin@email.com` `azertyuiop`

## Add Fixtures tests

* symfony console doctrine:fixtures:load

## Tests

* php bin/phpunit --testdox

### Start the environment

```
Composer install
symfony server:start
```
