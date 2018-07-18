<?
// Upgrage Todos UPG

/*

## PG Import

* check interessenbingung to delete
* review code
* integrate pull request
* fix migration typo organisation -> interessenbingung
* NO change organisation.sekretariat to textfield
* make DB migration
* marker für importierte fields updaten (updated_by_import, sekretariat)
* check my changes survive
* TODO run pg script
* add fields of Céline
* generate froms
* make DB migration on PROD
* archive pg PDFs like zb PDFs
* TODO upload forms
* TODO update lobbywatch.sql file
* add pg summary to mail
* TODO info benutzer (* sekretariat, * Beschreibung, * updated_by_import, * doppelte gelöscht, * PG PDF von Hand, Rechreschreib Fehler, Margrit vs Margret Kiener-Nellen, * HP von Gruppe wenn Sekretariat, * leider schlecht formatiert)


## PHP 7.2 and MySQL 5.7

* check debian PHP 5.7 and MySQL 5.7
* abel: docker mysql respect my.cnf config
* abel: PHP 7.2
* abel: setup php.ini for PHP 7.2
* abel: setup Apache2.4
* abel: docker MySQL 5.7
* abel: setup my.cnf for docker MySQL 5.7
* forms: fix sql_mode=only_full_group_by
* abel: create docker MySQL aliases
* abel: change back to 127.0.0.1 instead of localhost/socket (php.ini, my.cnf)
* run views refresh
* use docker mysqldump
* generate forms
* run ws.p
* abel: setup PhpMyAdmin
* abel: start mariaDB 10.2 on 3307
* upgrade scripts to PHP 7.2
* change scripts to docker MySQL 5.7
* RPIW: upgrade RPIW PHP 7.2
* RPIW: upgrade RPIW MySQL 5.7/MariaDB 10.2 --> create docker container based on RPIW using sid 5.7.22 image, see presentation
* RPIW: run ws.p
* understand /debian/pool/main
* drupal see PHP 7.2 errors
* patch Drupal
* cyon: patch Drupal
* abel: import mariaDB 10.2 on 3307
* abel: docker map into new file, do not override /etc/my.cnf
* tag new lw version
* forms: upload forms
* test Drupal PHP 7.2
* cyon: set PHP 7.2
* forms: utf-8 problem in partition
* infomail schreiben (deselect, upgraded PHP, MySQL, nichts sehen, benutzer Docker (neue Technologie), etwa 1w Arbeit, spam filter von Webseite gibt es nicht mehr)
* TODO upgrade tabula
* Update website with current requirements
* docker: tag and images upload
* write blog article inputrc (useful for Docker commands, only certain type)
* TODO write blog article MySQL 5.7 docker debian for RPi
* restore full backup on RPIW (structure may change)
* send bug report to SQLMaestro because of wrong array init / query_utils.php (create_function()) / mpdf.php (each())

## 2nd Prio docker/MySQL57/PHP72

* TODO DB: change collation to utf8_german2_ci
* improve DB scripts for 3307 port
* OK understand and see entrypoints
* clever mechanism for docker container names in scripts (env variable)
* enable sql_mode NO_ZERO_IN_DATE,NO_ZERO_DATE, fix db_views.sql
* replace CONCAT() with CONCAT_WS() in db_views.sql
* v_organisation_beziehung should use v_organisation_simple

## 2nd Prio
* handle partions with nonalphabetic name starts like ."«
* check custom files for changes
* Check utils.createLoadingModalDialog(localizer.getString('Deleting')).modal();
* Improve and update security
* Remove ^M in generated *.js files
* remove trailing whitespace in generated code, https://stackoverflow.com/questions/9532340/how-do-i-remove-trailing-whitespace-using-a-regular-expression
* Use public_html/bearbeitung/components/common_utils.php
* improve custom/custom_page.php

Done:
* Test application features
* Remove calls to $this->RenderText()
* Check UID WS Call from Button in Organisation Edit
* Remove UPG todo tags
* AJAX broken: Quick search
* AJAX broken: Detail records with +
* Update custom_templates
* Afterburner which necessary, clean up
* SelectedOperationGridState migration
* export lobbywatch.sql
* Migrate Sources to UTF-8
* Find latin-1 files
* Delete all public_html/bearbeitung files and detect unnecessary files
* remove convert_utf8() calls

*/

/*

## Custom features added to PHPGen framework

* Comments on edit forms
* Bulk Ops
* Enhanced comments on grid header
* Call UID WS
* Marked fields on edit/insert form
* Custom main.css
* Custom main.js
* Preview parlamentarier
* Default filter out retired parlamentarier [rework]
* Improved security [rework]

*/

/*
Test report 01.07.2018/Osaka

* Enhanced comments on grid header → OK
* Bulk Ops → OK
* Comments on edit forms → OK
* Marked fields on edit/insert form → OK
* Call UID WS → OK
* Custom main.css → OK
* Custom main.js → OK
* Preview parlamentarier
* Default filter out retired parlamentarier [rework]
* Improved security [rework]
