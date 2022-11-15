## System requirements

docker
docker-compose

download link: https://www.docker.com/

https://docs.docker.com/compose/install/

Or

Install manual:

- php 8
- nginx or apache2
- install gd
- install composer

## How to run project
If your machine has installed docker

Open cmd (Windows) - Terminal (linux or MacOS)

Copy file .env.example to .env and add setting for docker

Go to root folder of project and run command

```shell
docker-compose up -d
```

Install vendor for project:

```shell
docker-comopse exec php-fpm /bin/sh
```

in console run: `composer install`

after docker build success open this link:

http://localhost:{port}/graph

port in .env config for docker

eg: http://localhost:8081/graph

If install php and nginx or apache2 manual, open terminal cd to folder src and use this command:

Install vendor for project:

```shell
composer install
```

```shell
php artisan serve
```

when server start can connect with this link:

http://localhost:8000/graph

now you can try to submit an wiki link have table and get graph image generate by app.

## How to run test function with unit test of project
If your machine has installed docker

Open cmd (Windows) - Terminal (linux or MacOS)

start docker first and run command:

```shell
docker-compose exec php-fpm /bin/sh
```

and Run command:

```shell
php artisan test
```

If use php manual

Open terminal cd to folder src and run command:

```shell
php artisan test
```

You will see result of test in console.


