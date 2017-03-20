Lobbywatch.ch
=============

[Lobbywatch.ch](http://lobbywatch.ch) - the platform for transparent politics.

Lobbywatch.ch maintains a database with links of politicians and lobby groups.

This repository contains the

* edit forms,
* Drupal lobbywatch module for a textual representation,
* DB structure, and
* visualizations.

The data are not stored in this repository.

## Requirements

* PHP 7
* MySql 5.6 / MariaDB 10.1
* Drupal 7
* D3
* bash

More info, see https://cms.lobbywatch.ch/de/seite/technik

### Edit forms generation

The edit forms are built with the [PHP Generator for MySQL Professional](http://www.sqlmaestro.com/de/products/mysql/phpgenerator/). Its a commercial tool (about 100$).

PHP Generator for MySQL Professional 12.8.0.18

## Edit forms

### Build

    ./build.sh

### Deploy

    ./deploy.sh -s -p

See parameters with

    ./deploy.sh -h

## Drupal Theme

The Drupal 7 theme transparent_sky is in a separte git repository: https://github.com/Lobbywatch/transparent_sky

## License

Source of Lobbywatch.ch is licensed under GPL, see LICENSE.
