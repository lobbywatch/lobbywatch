<?
// Upgrage Todos UPG

/*

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
* TODO upgrade PHP mysql connector to lasted version
* TODO upgrade Python mysql connector to lasted version
* TODO upgrade Java mysql connector to lasted version
* TODO RPIW: upgrade RPIW MySQL 5.7/MariaDB 10.2 --> create docker container based on RPIW using sid 5.7.22 image, see presentation
* TODO RPIW: run ws.p
* understand /debian/pool/main
* drupal see PHP 7.2 errors
* TODO test Drupal PHP 7.2
* patch Drupal
* cyon: patch Drupal
* TODO cyon: set PHP 7.2
* TODO upgrade tabula
* send bug report to SQLMaestro because of wrong array init / query_utils.php (create_function()) / mpdf.php (each())
* TODO Update website with current requirements
* TODO abel: import mariaDB 10.2 on 3307
* restore full backup on RPIW (structure may change)

## 2nd Prio docker/MySQL57/PHP72

* understand and see entrypoints
* clever mechanism for docker container names in scripts (env variable)
* enable sql_mode NO_ZERO_IN_DATE,NO_ZERO_DATE, fix db_views.sql
* replace CONCAT() with CONCAT_WS() in db_views.sql
* v_organisation_beziehung should use v_organisation_simple

## 2nd Prio
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
