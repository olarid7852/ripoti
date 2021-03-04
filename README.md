#  Digital Rights Violation Reporting Platform
> A platform for reporting digital rights violations

## Requirements

- PHP
- [node](https://nodejs.org/)
- npm

## Development Branch
checkout to the `develop` branch
~~~
git checkout develop
~~~

## Environment Variables
- Make a copy of `.env.sample` to `.env` in the env directory.
- Fill in details as appropriate

## Installing Dependencies
#### Composer Dependencies
~~~
composer install
~~~

#### Node.js Dependencies
~~~
npm install
~~~

## Setting up the Database

#### Create Database
~~~
CREATE DATABASE `digital_rights_platform` CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci;
~~~

#### Running Migrations
~~~
./yii migrate/up --migrationPath=@vendor/cottacush/yii2-user-auth/migrations
./yii migrate/up --migrationPath=@vendor/cottacush/yii2-permissions-ext/migrations
./yii migrate/up --migrationPath=app/migrations
~~~

## Starting the Application
You can run the application in development mode by running this command from the project directory:

~~~
./yii serve
~~~

This will start the application on port 8080.

To run on a different port, run the following command from the project directory:

~~~
./yii serve localhost:<port>
~~~

## Contributors
- Apanpa Medinat `medinatapampa@yahoo.com`
- Meduna Oluwafemi `femimeduna@gmail.com`
- Ogedengbe Ireti `opteaoged@gmail.com`
- - -
